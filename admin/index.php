<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
$title_web = "Login Admin";
// --- 1. CEK COOKIE (Remember Me) ---
if (isset($_COOKIE['id_panitia']) && isset($_COOKIE['key'])) {
  $id_panitia = $_COOKIE['id_panitia'];
  $key = $_COOKIE['key'];

  // Ambil rt_panitia dari database berdasarkan cookie
  $stmt = mysqli_prepare($conn, "SELECT rt_panitia FROM admin WHERE rt_panitia = ?");
  mysqli_stmt_bind_param($stmt, "s", $id_panitia);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($result);

  // Verifikasi cookie: Cek apakah key cocok dengan hash rt_panitia
  if ($key === hash('sha256', $row['rt_panitia'])) {
    session_unset();
    session_destroy();
    session_start();

    $_SESSION['login'] = true;
    $_SESSION['rt_panitia'] = $row['rt_panitia'];
  }
}

// --- 2. REDIRECT JIKA SUDAH LOGIN ---
if (isset($_SESSION['login'])) {
  header("Location: dashboard/index.php");
  exit;
}

// --- 3. PROSES LOGIN UTAMA ---
if (isset($_POST['login'])) {

  $rt_panitia = $_POST['rt_panitia']; // Input dari form
  $sandi = $_POST['sandi'];

  // Query mencari berdasarkan PRIMARY KEY (rt_panitia)
  $stmt = mysqli_prepare($conn, "SELECT * FROM admin WHERE rt_panitia = ?");
  mysqli_stmt_bind_param($stmt, "s", $rt_panitia);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  // Cek apakah RT Panitia ditemukan
  if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    // Verifikasi Password
    if (password_verify($sandi, $row['sandi'])) {

      // Set Session
      session_unset();
      session_destroy();
      session_start();
      $_SESSION['login'] = true;
      $_SESSION['rt_panitia'] = $row['rt_panitia'];

      // Simpan rt_panitia ke cookie (berlaku 1 jam)
      setcookie('id_panitia', $row['rt_panitia'], time() + 3600, "/admin");

      // Buat key validasi (hash dari rt_panitia)
      setcookie('key', hash('sha256', $row['rt_panitia']), time() + 3600, "/admin");

      header("Location: dashboard/index.php");
      exit;
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
  <div class="login-container" id="loginForm">
    <div class="login-header">ADMIN</div>
    <div class="login-box">
      <div class="user-icon">
        <img src="/assets/img/icon.png" alt="User Icon">
      </div>
      <form class="login-form" action="" method="post">
        <label for="login-name">Login</label>
        <select id="login-name" name="rt_panitia">
          <?php
          $stmt = $conn->prepare("SELECT * FROM admin");
          $stmt->execute();
          $result = $stmt->get_result();
          if (mysqli_num_rows($result) > 0) {
            // 4. Looping: Mengulang kode HTML sebanyak jumlah baris data (i kali)
            while ($row = mysqli_fetch_assoc($result)) {

              // Menampung data database ke variabel agar mudah dibaca
              $rtrw = $row['rt_panitia'];

              // Tentukan warna tombol status (Opsional: Agar tampilan lebih bagus)
              // Jika status 'Sudah Memilih' tombol jadi hijau, jika belum jadi kuning/merah
          ?>

              <option value=<?php echo "'$rtrw'"; ?> selected><?php echo "$rtrw"; ?></option>

          <?php
            } // Akhir dari loop while
          } else {
            // Jika data kosong (0 baris)
            echo "<tr><td colspan='5' class='text-center'>Belum ada data admin.</td></tr>";
          }
          ?>
        </select>

        <label for="password">Password</label>
        <input type="password" id="password" name="sandi" placeholder="Masukkan password Anda" required>

        <div class="button-box">
          <button type="submit" class="btn-login" name="login">Kirim</button>
        </div>
      </form>
    </div>
    <div class="footer-text">
      <?php if (isset($error)) : ?>
        <h3 class="error">Password <?php echo $rt_panitia ?> salah!</h3>
      <?php endif; ?>
      <h3>E-Voting RT/RW</h3>
      <p>Sistem Pemilihan RT/RW secara Online</p>
    </div>
  </div>
</body>
<script>

</script>

</html>