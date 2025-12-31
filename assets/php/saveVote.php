<?php
session_start();
if (!isset($_SESSION["login_warga"])) {
  header("Location: /warga");
  exit;
}
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
session_start();
$nama_warga = $_SESSION['nama_warga'];
$id_warga = $_SESSION['id_warga'];
$nama_pilihan_kandidat = $_POST['nama_pilihan_kandidat'];
$stmt = $conn->prepare("select no_kandidat, id_sesi from kandidat where nama_kandidat = ?");
$stmt->bind_param("s", $nama_pilihan_kandidat);
$stmt->execute();
$result = $stmt->get_result();
$pilihan_kandidat = mysqli_fetch_assoc($result);
$no_pilihan_kandidat = $pilihan_kandidat['no_kandidat'];
$id_sesi = $pilihan_kandidat['id_sesi'];
$stmt->close();
$stmt2 = $conn->prepare("UPDATE warga SET status = 'Sudah Memilih' WHERE id_warga = ?");
$stmt2->bind_param("i", $id_warga);
$stmt2->execute();
$result = $stmt2->get_result();
$stmt2->close();
$stmt2 = $conn->prepare("INSERT INTO kotak_suara (no_kandidat, id_sesi) VALUES (?,?)");
$stmt2->bind_param("ii", $no_pilihan_kandidat, $id_sesi);
$stmt2->execute();
$result = $stmt2->get_result();
$stmt2->close();
?>
<script>
  window.location.href = '/warga/selesai/index.php'
</script>