<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
$nama_sesi = $_POST['nama'];
$tgl_mulai = $_POST['tgl_mulai'];
$jam_mulai = $_POST['jam_mulai'];
$tgl_selesai = $_POST['tgl_selesai'];
$jam_selesai = $_POST['jam_selesai'];

$start_datetime = $tgl_mulai . ' ' . $jam_mulai . ':00'; // Tambah :00 untuk detik
$end_datetime   = $tgl_selesai . ' ' . $jam_selesai . ':00';

$foto_sampul = $_FILES['foto_sampul']['name'];
$foto_sampul_temp = $_FILES['foto_sampul']['tmp_name'];

$rt_panitia = $_POST['rt_panitia'];

if (isset($foto_sampul) && $_FILES['foto_sampul']['error'] != UPLOAD_ERR_NO_FILE) {
    $ekstensiFile  = strtolower(pathinfo($foto_sampul, PATHINFO_EXTENSION));
    $namaBaru = "sessionpic_" . time() . "." . $ekstensiFile;
    $folder = "../../assets/img/";
    $target = $folder . $namaBaru;
    $stmt = $conn->prepare("INSERT INTO sesi_pemilihan (nama_sesi, waktu_mulai, waktu_selesai, foto_sampul, rt_panitia) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "sssss", $nama_sesi, $start_datetime, $end_datetime, $target, $rt_panitia);
    mysqli_stmt_execute($stmt);
    move_uploaded_file($foto_sampul_temp, $target);
} else {
    $stmt = $conn->prepare("INSERT INTO sesi_pemilihan (nama_sesi, waktu_mulai, waktu_selesai, rt_panitia) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "ssss", $nama_sesi, $start_datetime, $end_datetime, $rt_panitia);
    mysqli_stmt_execute($stmt);
}

$stmt_update_status = $conn->prepare("UPDATE sesi_pemilihan 
        SET status = CASE 
            -- Jika sudah diarsipkan manual, jangan diotak-atik sistem otomatis
            WHEN status = 'Diarsipkan' THEN 'Diarsipkan'
            
            -- Jika waktu sekarang belum mencapai waktu mulai
            WHEN NOW() < waktu_mulai THEN 'Persiapan'
            
            -- Jika waktu sekarang sudah lewat mulai TAPI belum lewat selesai
            WHEN NOW() >= waktu_mulai AND NOW() < waktu_selesai THEN 'Aktif'
            
            -- Jika waktu sekarang sudah lewat waktu selesai
            WHEN NOW() >= waktu_selesai THEN 'Selesai'
            
            -- Fallback (jaga-jaga)
            ELSE status
        END
        ");
mysqli_stmt_execute($stmt_update_status);

?>
<script>
    window.location.href = "/admin/dashboard"
</script>