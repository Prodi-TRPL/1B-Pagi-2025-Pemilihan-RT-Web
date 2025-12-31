<?php
$title_web = "Akses Sebagai";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title_web ?></title>
  <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
  <link rel="stylesheet" href="/assets/css/aksessebagai/style.css">
</head>

<body>
  <main>
    <section class="hero">
      <div class="welcome-box">
        <p>Selamat Datang di aplikasi KlikPilih!<br>Sudah menentukan kandidat pilihanmu?</p>
      </div>
      <hr>
      <h3 class="akses-text">Akses Sebagai?</h3>

      <div class="container">
        <a href="/admin/">
          <div class="card">
            <img src="/assets/img/ADMIN1.png" alt="Admin">
            <div class="label">Admin</div>
          </div>
        </a>

        <p class="or-text">Atau</p>

        <a href="/warga/">
          <div class="card">
            <img src="/assets/img/WARGA.png" alt="Warga">
            <div class="label">Warga</div>
          </div>
        </a>
      </div>
      <p class="desk">Superadmin dan Panitia login melalui Admin</p>
    </section>
  </main>
</body>

</html>