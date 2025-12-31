<?php
if (!isset($_SESSION["login"])) {
  header("Location: /admin");
  exit;
}
$host = "localhost";
$user = "klikpilih";
$pass = "PBLkel3~!@#$%%$#@!~"; // Sesuaikan dengan password database Anda
$db   = "klikpilih";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>