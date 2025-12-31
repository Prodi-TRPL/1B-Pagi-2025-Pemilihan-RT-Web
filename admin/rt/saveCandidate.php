<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: /admin");
    exit;
}
header('Content-Type: application/json');
$no = array_map(fn($n) => $n - 1, $_POST['no_kandidat']);
$nama_kandidat = $_POST['nama_kandidat'];
$visi_kandidat = $_POST['visi_kandidat'];
$misi_kandidat = $_POST['misi_kandidat'];
$namaFile = $_FILES['foto_kandidat']['name'];
$tmpName  = $_FILES['foto_kandidat']['tmp_name'];
$id_sesi = $_POST['id_sesi'];
$rt_panitia = $_POST['rt_panitia'];
$conn = mysqli_connect("localhost", "klikpilih", "PBLkel3~!@#$%%$#@!~", "klikpilih");

$jumlah_kandidat = count($nama_kandidat);
$result = $conn->query("SELECT * FROM kandidat where id_sesi = $id_sesi;");
// $tesbanyak = [];
if ($result->num_rows == 0) {
    for ($i = 0; $i < $jumlah_kandidat; $i++) {
        if (isset($namaFile) && $_FILES['foto_sampul']['error'] != UPLOAD_ERR_NO_FILE) {
            $stmt = $conn->prepare("INSERT INTO kandidat (nama_kandidat, visi, misi, rt_panitia, foto_kandidat, id_sesi) VALUES (?, ?, ?, ?, ?, ?)");
            $ekstensiFile  = strtolower(pathinfo($namaFile[$i], PATHINFO_EXTENSION));
            $namaBaru = "profile_" . $i . "_" . time() . "." . $ekstensiFile;
            $folder = "../../assets/img/foto_kandidat/";
            $target = $folder . $namaBaru;
            $stmt->bind_param("sssssi", $nama_kandidat[$i], $visi_kandidat[$i], $misi_kandidat[$i], $rt_panitia, $target, $id_sesi);
            mysqli_stmt_execute($stmt);
            move_uploaded_file($tmpName[$i], $target);
        } else {
            $stmt = $conn->prepare("INSERT INTO kandidat (nama_kandidat, visi, misi, rt_panitia, id_sesi) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $nama_kandidat[$i], $visi_kandidat[$i], $misi_kandidat[$i], $rt_panitia, $id_sesi);
            mysqli_stmt_execute($stmt);
        }
    }
} else {

    // 1. Siapkan template query sekali saja di LUAR loop (menggunakan tanda ?)
    // Asumsi: Anda ingin mengurangi nilai, bukan menambah teks "-1". 
    // Jika ingin teks, hapus logika matematikanya.
    $stmt = $conn->prepare("SELECT no_kandidat FROM kandidat WHERE id_sesi = ?");
    $stmt->bind_param("i", $id_sesi);
    $stmt->execute();
    $no_kandidat = $stmt->get_result()->fetch_assoc()['no_kandidat'] ?? null;
    $i = -1;
    while ($row = $result->fetch_assoc()) {
        $i++;
        $nama_baru = $nama_kandidat[$i];
        $visi_baru = $visi_kandidat[$i];
        $misi_baru = $misi_kandidat[$i];
        $no_kandidat_baru = $row['no_kandidat'];
        if (isset($namaFile) && $_FILES['foto_sampul']['error'] != UPLOAD_ERR_NO_FILE) {
            $foto_baru = $foto_kandidat[$i];
            $ekstensiFile  = strtolower(pathinfo($namaFile[$i], PATHINFO_EXTENSION));
            $namaBaru = "profile_" . $i . "_" . time() . "." . $ekstensiFile;
            $folder = "../../assets/img/foto_kandidat/";
            $target = $folder . $namaBaru;
            $stmt = $conn->prepare("UPDATE kandidat SET nama_kandidat = ?, visi = ?, misi = ?, foto_kandidat = ? WHERE no_kandidat = ?");
            mysqli_stmt_bind_param($stmt, "ssssi", $nama_baru, $visi_baru, $misi_baru, $target, $no_kandidat_baru);
            mysqli_stmt_execute($stmt);
            move_uploaded_file($tmpName[$i], $target);
        } else {
            $stmt = $conn->prepare("UPDATE kandidat SET nama_kandidat = ?, visi = ?, misi = ? WHERE no_kandidat = ?");
            mysqli_stmt_bind_param($stmt, "sssi", $nama_baru, $visi_baru, $misi_baru, $no_kandidat_baru);
            mysqli_stmt_execute($stmt);
        }
    }
    mysqli_stmt_close($stmt);
}
