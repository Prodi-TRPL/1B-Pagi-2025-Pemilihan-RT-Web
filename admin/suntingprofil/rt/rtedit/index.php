<?php

// Cek apakah user memiliki session login
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: /admin");
    exit;
}
$title_web = "Sunting Profil Admin";
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
$id_rt = $_GET['id'];
$query = "SELECT * FROM admin where rt_panitia like ? limit 1 offset ?";
$stmt = $conn->prepare($query);
$id_rt = $id_rt - 1;
$rtonly = "RT%";
$stmt->bind_param("si", $rtonly, $id_rt);
$stmt->execute();
$result = $stmt->get_result();
if (mysqli_num_rows($result) == 0) {
    echo '<script>
    window.location.href = "/admin/kelolaadmin"
</script>';
}
$rt___a = mysqli_fetch_assoc($result)['rt_panitia'];
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
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-exit.html'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/popup-delete-acc.php'; ?>
    <form id="form" action="editAcc.php" method="post">
        <div class="button-group-2">
            <button class="delete" type="button" onclick="hapus()">Hapus</button>
            <button class="cancel" type="button" onclick="batal()">Batal</button>
            <button class="confirm" type="submit" name="selesai">Selesai</button>
        </div>
        <div class="konten-utama">
            <div class="container-form">
                <div class="login-form">
                    <div class="login-group">
                        <label for="rt">RT</label>
                        <input type="text" id="rt" name="rt" value="<?php echo $rt___a; ?>" readonly>
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

</body>
<script>
    const form = document.getElementById('form');
    const pw = document.getElementById('sandibaru');
    const kPw = document.getElementById('konfirmasisandi');
    form.addEventListener('submit', function(event) {
        if (pw.value != kPw.value) {
            event.preventDefault();
            alert("Pw tidak sama");
        }
    });
    const allCancel = document.querySelectorAll('.cancel');

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

    function batal() {
        window.location.href = '/admin/kelolaadmin';
    }
    popupdeleteContainer = document.querySelector('.popupdelete-container');
    popupdelete = document.getElementById('popupdelete');
    function hapus() {
        popupdeleteContainer.classList.add('open');
        popupdelete.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closePopupDelete() {
        popupdeleteContainer.classList.remove('open');
        popupdelete.classList.remove('open');
        document.body.style.overflow = 'auto';
    }
    
</script>

</html>
