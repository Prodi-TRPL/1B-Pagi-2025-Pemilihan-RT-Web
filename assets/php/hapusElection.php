<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: /admin");
  exit;
}
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
$id = $_POST['id_select_sesi_pemilihan'] - 1;
if ($_SESSION['rt_panitia'] == 'RW') {
  $pemilihan_sesi_rt = '';
} else {
  $pemilihan_sesi_rt = 'where rt_panitia = "' . $_SESSION['rt_panitia'] . '"';
}
$sql_cari = "SELECT id_sesi FROM sesi_pemilihan $pemilihan_sesi_rt ORDER BY id_sesi ASC LIMIT 1 OFFSET $id";
$result = $conn->query($sql_cari);
if ($result->num_rows > 0) {
  // Ambil datanya
  $row = mysqli_fetch_assoc($result);
  $row_id_sesi_sesi_pemilihan = $row['id_sesi'];
  $stmt = $conn->prepare("delete from sesi_pemilihan where id_sesi = ?");
  $stmt->bind_param("i", $row_id_sesi_sesi_pemilihan);
  mysqli_stmt_execute($stmt);
}
?>
<script>
  window.location.href = "/admin/dashboard"
</script>