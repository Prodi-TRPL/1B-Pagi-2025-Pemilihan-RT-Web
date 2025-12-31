<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: /admin");
    exit;
}
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';

$rt = $_POST['rt'];

$query = "SELECT * FROM admin";
$stmt = $conn->prepare("delete from admin where rt_panitia = ?");
$stmt->bind_param("s", $rt);
$stmt->execute();
// echo $rt;
?>
<script>
    window.location.href = "/admin/kelolaadmin"
</script>