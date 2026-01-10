<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "klikpilih";
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
$sql = "UPDATE sesi_pemilihan 
        SET status = CASE 
            WHEN status = 'Diarsipkan' THEN 'Diarsipkan'
            
            WHEN NOW() < waktu_mulai THEN 'Persiapan'
            
            WHEN NOW() >= waktu_mulai AND NOW() < waktu_selesai THEN 'Aktif'
            
            WHEN NOW() >= waktu_selesai THEN 'Selesai'
            
            ELSE status
        END
        ";

$stmt = $conn->prepare($sql);
$stmt->execute();
