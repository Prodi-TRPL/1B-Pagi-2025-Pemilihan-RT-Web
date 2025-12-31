<?php 
session_start();

// Cek apakah user memiliki session login
if( !isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Admin</title>
  <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
</head>
<body>

    <h1>Selamat Datang, Admin!</h1>
    <p>Ini adalah halaman rahasia yang hanya bisa diakses jika Anda sudah login.</p>
    
    <a href="logout.php" style="color: red;">Logout</a>

</body>
</html>