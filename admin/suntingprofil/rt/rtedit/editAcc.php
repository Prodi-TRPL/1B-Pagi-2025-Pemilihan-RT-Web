<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: /admin");
    exit;
}
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$rt = $_POST['rt'];
$nohp = $_POST['nohp'];
$pw = $_POST['pw'];
$pw_hash = password_hash($pw, PASSWORD_DEFAULT);


if (str_contains($email, '@')) {
    $stmt = $conn->prepare("UPDATE admin set email = ?, no_hp = ?, sandi = ? where rt_panitia = ?");
    $stmt->bind_param("ssss", $email, $nohp, $pw_hash, $rt);
    $stmt->execute();
} else {
    echo '<script>
    window.location.href = "./index.php?id=' . $id . '&error=invalidemail"
</script>';
}

?>

<script>
    window.location.href = "/admin/kelolaadmin"
</script>