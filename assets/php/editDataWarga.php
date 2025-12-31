<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: /admin");
  exit;
}
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
$nama_warga = $_POST['nama'];
$status = $_POST['status'];
$id = $_POST['id_select_warga'] - 1;
$id_sesi = $_POST['id_sesi'];
$rt_panitia = $_POST['rt_panitia'];
// echo $id;
$pemilihan_sesi_rt = 'where rt_panitia = "' . $rt_panitia . '" AND id_sesi = "' . $id_sesi . '"';
$sql_cari = "SELECT token FROM warga $pemilihan_sesi_rt ORDER BY id_warga ASC LIMIT 1 OFFSET $id";
$result = $conn->query($sql_cari);
if ($result->num_rows > 0) {
  // Ambil datanya
  $row = mysqli_fetch_assoc($result);
  $row_token_warga = $row['token'];
  $query = "SELECT * FROM admin";
  $stmt = $conn->prepare("UPDATE warga SET nama_warga = ? , status = ? where token = ?");
  $stmt->bind_param("sss", $nama_warga, $status, $row_token_warga);
  $stmt->execute();
}
?>
<script>
  window.location.href = "/admin/rt/index.php?rt=<?php echo $rt_panitia ?>&id_sesi=<?php echo $id_sesi ?>"
</script>