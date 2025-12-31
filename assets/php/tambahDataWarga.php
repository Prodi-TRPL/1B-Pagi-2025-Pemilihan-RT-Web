<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: /admin");
  exit;
}
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
$nama_warga = $_POST['nama'];
$status = $_POST['status'];
$id_sesi = $_POST['id_sesi'];
$rt_panitia = $_POST['rt_panitia'];
$stmt = $conn->prepare("insert into warga (nama_warga, status, rt_panitia, id_sesi) values (?, ?, ?, ?);");
$stmt->bind_param("sssi", $nama_warga, $status, $rt_panitia, $id_sesi);
mysqli_stmt_execute($stmt);

?>
<script>
    window.location.href = "/admin/rt/index.php?rt=<?php echo $rt_panitia ?>&id_sesi=<?php echo $id_sesi ?>"
</script>