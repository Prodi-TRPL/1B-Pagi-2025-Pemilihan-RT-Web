<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: /admin");
    exit;
}
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';

$nama = $_POST['nama'];
$email = $_POST['email'];
$rt = $_POST['rt'];
$nohp = $_POST['nohp'];
$pw = $_POST['pw'];
$pw_hash = password_hash($pw, PASSWORD_DEFAULT);
$stmt = $conn->prepare("SELECT * FROM admin where rt_panitia = ?");
$stmt->bind_param("s", $rt);
$stmt->execute();
$result = $stmt->get_result();
$row = mysqli_fetch_assoc($result);

if (password_verify($_GET['pwlama'], $row['sandi'])) {
    $stmt = $conn->prepare("UPDATE admin set email = ?, no_hp = ?, sandi = ? where rt_panitia = ?");
    $stmt->bind_param("ssss", $email, $nohp, $pw_hash, $rt);
    $stmt->execute();
} else {
    echo "<script>alert('Password lama salah'); window.location.href = '/admin/suntingprofil'</script>";
}

?>

<script>
    window.location.href = "/admin/dashboard"
</script>