<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';

// 1. Cek Cookie (Fitur Remember Me)
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    // Ambil username berdasarkan ID

    $stmt = $conn->prepare("SELECT username FROM admins WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);

    // Cek kecocokan cookie key dengan hash username
    if ($key === hash('sha256', $row['username'])) {
        $_SESSION['login'] = true;
    }
}

// 2. Jika sudah login (Session ada), langsung pindah ke admin.php
if (isset($_SESSION['login'])) {
    header("Location: admin.php");
    exit;
}

// 3. Proses Login saat tombol ditekan
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Gunakan Prepared Statement (Aman dari SQL Injection)
    $stmt = mysqli_prepare($conn, "SELECT * FROM admins WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Cek Username
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Cek Password (Verify Hash)
        if ($password == $row['password']) {

            // Set Session
            $_SESSION['login'] = true;

            // Cek Remember Me
            if (isset($_POST['remember'])) {
                // Buat cookie berlaku 1 jam (3600 detik)
                setcookie('id', $row['id'], time() + 3600, "/");
                // Enkripsi username untuk key keamanan cookie
                setcookie('key', hash('sha256', $row['username']), time() + 3600, "/");
            }

            header("Location: admin.php");
            exit;
        }
    }

    $error = true;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Halaman Login Admin</title>
  <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            padding-top: 50px;
        }

        form {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            width: 300px;
        }

        input {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #6cd670;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    <form action="" method="post">
        <h2 style="text-align:center;">Login Admin</h2>

        <?php if (isset($error)) : ?>
            <p class="error">Username atau Password salah!</p>
        <?php endif; ?>

        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <label style="display: flex; align-items: center; gap: 5px;">
            <input type="checkbox" name="remember" style="width: auto;"> Remember Me
        </label>
        <br>

        <button type="submit" name="login">Login</button>
    </form>

</body>

</html>