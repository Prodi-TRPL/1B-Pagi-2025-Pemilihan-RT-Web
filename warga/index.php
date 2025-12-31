<?php
session_start();
$title_web = "Login Warga";
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';

// --- 1. CEK COOKIE (Remember Me Khusus Warga) ---
if (isset($_COOKIE['id_warga']) && isset($_COOKIE['key_warga'])) {
  $id = $_COOKIE['id_warga'];
  $key = $_COOKIE['key_warga'];

  // Ambil data warga berdasarkan ID
  $stmt = mysqli_prepare($conn, "SELECT id_warga, nama_warga FROM warga WHERE id_warga = ?");
  mysqli_stmt_bind_param($stmt, "i", $id); // "i" karena id integer
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);

  // Cek kecocokan cookie key dengan hash nama_warga
  if ($key === hash('sha256', $row['id_warga'])) {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['login_warga'] = true; // Nama session beda dengan admin
    $_SESSION['id_warga'] = $row['id_warga'];
  }
}

// --- 2. REDIRECT JIKA SUDAH LOGIN ---
if (isset($_SESSION['login_warga'])) {
  header("Location: votingkandidat/index.php");
  exit;
}
// --- 3. PROSES LOGIN WARGA ---
if (isset($_POST['login_warga'])) {

  $nama = $_POST['nama_warga'];
  $token = $_POST['token'];
  // Query mencari berdasarkan NAMA WARGA
  // Catatan: Jika ada nama kembar, query ini akan mengambil yang pertama ditemukan.
  // Idealnya login menggunakan NIK atau Email yang pasti unik.
  $stmt = mysqli_prepare($conn, "SELECT w.*, s.status AS status_sesi 
          FROM warga w 
          JOIN sesi_pemilihan s ON w.id_sesi = s.id_sesi 
          WHERE w.nama_warga = ?");
  mysqli_stmt_bind_param($stmt, "s", $nama);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // Cek apakah Nama ditemukan
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      // Verifikasi Token (Password Verify)
      if ($token == $row['token']) {
        session_unset();
        session_destroy();
        session_start();
        // Set Session Khusus Warga
        $_SESSION['login_warga'] = true;
        $_SESSION['nama_warga'] = $row['nama_warga'];
        $_SESSION['id_warga'] = $row['id_warga'];

        // Simpan ID warga ke cookie
        setcookie('id_warga', $row['id_warga'], time() + 3600, "/warga");
        // Buat key validasi (hash dari nama_warga)
        setcookie('key_warga', hash('sha256', $row['nama_warga']), time() + 3600, "/warga");

        $status_saat_ini = $row['status_sesi'];

        switch ($status_saat_ini) {
          case 'Persiapan':
            header("Location: menunggu/index.php");
            break;

          case 'Aktif':
            header("Location: votingkandidat/index.php");
            break;

          case 'Selesai':
            header("Location: selesai/index.php");
            break;

          default:
            // Jika statusnya 'Diarsipkan' atau error
            echo "Pemilihan tidak aktif atau diarsipkan.";
            break;
        }
        exit;
      }
    }
  }
  $error = true;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $title_web ?></title>
  <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
  <link rel="stylesheet" href="/assets/css/adminUserLogin/style.css">
</head>

<body>
  <div class="login-container">
    <div class="login-header">WARGA</div>
    <div class="login-box">
      <div class="user-icon">
        <img src="/assets/img/icon.png" alt="User Icon">
      </div>

      <form class="login-form" action="" method="post">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama_warga" placeholder="Masukkan nama Anda" required>

        <label for="token">Token</label>
        <input type="text" id="token" name="token" placeholder="Masukkan token Anda" required>
        <div class="button-box">
          <button type="submit" class="btn-login" name="login_warga">Kirim</button>
        </div>
      </form>
    </div>
    <div class="footer-text">
      <?php if (isset($error)) : ?>
        <h3 class="error">Nama dan Token salah!</h3>
      <?php endif; ?>
      <h3>E-Voting RT/RW</h3>
      <p>Sistem Pemilihan RT/RW secara Online</p>
    </div>
  </div>
</body>
<script>
</script>

</html>