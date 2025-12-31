<?php

// Cek apakah user memiliki session login
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: /admin");
    exit;
}
$title_web = "Tambah Profil Admin";
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
$stmt = $conn->prepare("SELECT * FROM admin where rt_panitia like ?");
$strrt = "RT%";
$stmt->bind_param("s", $strrt);
$stmt->execute();
$result = $stmt->get_result(); 
$row = mysqli_num_rows($result);
$row = $row + 1;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/suntingprofil/style.css">
    <title><?php echo $title_web ?></title>
    <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/header.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/sidebar.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/navbar.php'; ?>
    <form id="form" action="tambahAcc.php" method="post">
        <div class="button-group-2">
            <button class="cancel" type="button" onclick="batal()">Batal</button>
            <button class="confirm" type="submit" name="selesai">Selesai</button>
        </div>
        <a href="/admin/dashboard/"></a>
        <div class="konten-utama">
            <div class="container-form">
                <div class="login-form">
                    <div class="login-group">
                        <label for="rt">RT</label>
                        <input type="number" id="rt" name="rt" onchange="formatRT(this)" value=<?php echo $row; ?> min=<?php echo $row; ?> max="10" placeholder="RT" required>
                    </div>
                </div>
                <div class="login-form">
                    <div class="login-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="login-group">
                        <label for="no.hp">No.HP</label>
                        <input type="text" id="no.hp" name="nohp" placeholder="No.HP" required>
                    </div>
                </div>
                <div class="login-form">
                    <div class="login-group">
                        <label for="sandibaru">Kata Sandi Baru</label>
                        <input type="password" id="sandibaru" name="pw" placeholder="***" required>
                    </div>
                    <div class="login-group">
                        <label for="konfirmasisandi">Konfirmasi Kata Sandi</label>
                        <input type="password" id="konfirmasisandi" name="konfpw" placeholder="***" required>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                </div>
            </div>
        </div>
    </form>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-exit.html'; ?>

</body>
<script>
    function selesai() {
        window.location.href = "/admin/kelolaadmin"
    }

    function hapus() {
        window.location.href = "/admin/kelolaadmin"
    }

    function batal() {
        window.location.href = "/admin/kelolaadmin"
    }
    const allCancel = document.querySelectorAll('.cancel');
    const ok = document.getElementById('ok');

    allCancel.forEach(function(cancel) {
        cancel.addEventListener('click', () => {
            popupexitContainer.classList.remove('open');
            popupexit.classList.remove('open');
            popupmenuContainer.classList.remove('open');
            popupmenu.classList.remove('open');
            document.body.style.overflow = 'auto';
        });
    })

    

    function logoutbtn() {
        window.location.href = '/assets/php/logout.php';
    }

    function formatRT(input) {
        // Ambil nilai input
        let val = input.value;

        // Cek apakah nilainya ada dan hanya 1 digit (0-9)
        if (val.length === 1 && val >= 0) {
            // Tambahkan 0 di depannya
            input.value = "0" + val;
            console.log(input.value);
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        const rtInput = document.getElementById('rt');
        formatRT(rtInput); // Panggil fungsi formatRT saat halaman dimuat
    });
</script>

</html>