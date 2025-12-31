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
$rt = "RT " . $rt;

if (str_contains($email, '@')) {
    $stmt = mysqli_prepare($conn, "INSERT INTO admin (rt_panitia, sandi, email, no_hp) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $rt, $pw_hash, $email, $nohp);
    mysqli_stmt_execute($stmt);
} else {
    echo '<script>
    window.location.href = "./index.php&error=invalidemail"
</script>';
}
?>

<script>
    window.location.href = "/admin/kelolaadmin"
</script>