<?php

// Cek apakah user memiliki session login
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: /admin");
    exit;
}
$title_web = "Sunting Profil";
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
$nama_rt = $_SESSION['rt_panitia'];
$query = "SELECT * FROM admin where rt_panitia = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nama_rt);
$stmt->execute();
$result = $stmt->get_result();
$attr = mysqli_fetch_assoc($result)

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
    <form id="form" action="editAccSuperAdmin.php" method="post">
        <div class="button-group-2">
            <button class="cancel" type="button" onclick="batal()">Batal</button>
            <button class="confirm" type="submit" name="selesai">Selesai</button>
        </div>
        <div class="konten-utama">
            <!-- <div class="photo-upload" onclick="this.querySelector('input[type=file]').click()">
                <img src="/assets/img/image 6.png" alt="Upload Icon" class="camera-icon" />
                <input type="file" id="foto${i}" accept="image/*" style="display:none;" onchange="previewImage(this, 'preview${i}')">
            </div> -->
            <!-- <u>Ubah Foto Profil</u> -->
            <div class="container-form">
                <div class="login-form">
                    <div class="login-group">
                        <label for="rt">RT</label>
                        <input type="text" id="rt" name="rt" value="<?php echo $attr['rt_panitia']; ?>" readonly>
                    </div>
                    <div class="login-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>
                </div>
                <div class="login-form">
                    <div class="login-group">
                        <label for="no.hp">No.HP</label>
                        <input type="text" id="no.hp" name="nohp" placeholder="No.HP" required>
                    </div>
                    <div class="login-group">
                        <label for="sandilama">Kata Sandi Lama</label>
                        <input type="password" id="sandilama" name="pwlama" placeholder="***" required>
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
                </div>
            </div>
        </div>
    </form>

</body>
<script>
    const form = document.getElementById('form');
    const pw = document.getElementById('sandibaru');
    const lPw = document.getElementById('sandilama');
    const kPw = document.getElementById('konfirmasisandi');
    form.addEventListener('submit', function(event) {
        if (pw.value != kPw.value) {
            event.preventDefault();
            alert("Pw tidak sama");
        }
    });
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

    // ok.addEventListener('click', (e) => {
    //     e.preventDefault();
    //     popupmenuContainer.classList.remove('open');
    //     popupmenu.classList.remove('open');
    //     document.body.style.overflow = 'auto';
    // });

    function logoutbtn() {
        window.location.href = '/assets/php/logout.php';
    }
</script>

</html>