<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
// Cek apakah user memiliki session login
if (!isset($_SESSION["login_warga"])) {
    header("Location: ../");
    exit;
}
$title_web = "Menunggu Hasil Akhir";
$id_warga = $_SESSION['id_warga'];
// --- Langkah 1: Cari ID Sesi di tabel Warga ---
$stmt = $conn->prepare("SELECT id_sesi FROM warga WHERE id_warga = ?");
$stmt->bind_param("i", $id_warga); // "s" = string
$stmt->execute();
$result = $stmt->get_result();
$row_warga = $result->fetch_assoc();

// Ambil ID-nya (gunakan null coalescing '??' biar aman kalau kosong)
$id_sesi = $row_warga['id_sesi'] ?? null;

// PENTING: Tutup statement 1 sebelum lanjut
$stmt->close();


// --- Langkah 2: Cari Waktu Selesai (hanya jika ID Sesi ketemu) ---
$waktu_selesai = null; // Default value


$stmt = $conn->prepare("SELECT status, waktu_selesai FROM sesi_pemilihan WHERE id_sesi = ?");
$stmt->bind_param("i", $id_sesi); // "i" = integer
$stmt->execute();

$result_sesi = $stmt->get_result();
$row_sesi = $result_sesi->fetch_assoc();

$waktu_selesai = $row_sesi['waktu_selesai'] ?? null;
$status_sesi = $row_sesi['status'] ?? null;

$stmt->close();

// echo $_SESSION['rt_panitia'];
$stmt = mysqli_prepare($conn, "SELECT status FROM warga WHERE id_warga = ?");
mysqli_stmt_bind_param($stmt, "i", $id_warga);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$status_warga = mysqli_fetch_assoc($result);
if ($status_warga['status'] == 'Belum Memilih' && $status_sesi == 'Aktif') {
    header("Location: /warga/votingkandidat/index.php");
}
if ($status_sesi == 'Persiapan') {
    header("Location: /warga/menunggu/index.php");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <title><?php echo $title_web ?></title>
    <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
    <link rel="stylesheet" href="/assets/css/countdown/style.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/header.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/sidebar.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/navbar.php'; ?>
    <!-- <div class="konten-utama">
        <img src="/assets/img/voting.png" alt="icon voting" class="img-voting">
        <p class="text1">Terimakasih telah memilih kandidat</p>
        <p class="text2">Sedang menunggu hasil perolehan suara</p>
        <a href="/"><button type="button" class="exit-menu">Keluar</button></a>
    </div> -->
    <div class="container2">
        <div class="container">
            <div class="header">
                <div class="icon">
                    <img src="/assets/img/voting.png" alt="icon voting" class="img-voting">
                </div>
                <h1 class="title">Terima kasih telah memilih kandidat</h1>
                <p class="subtitle">Sedang menunggu hasil perolehan suara</p>
            </div>

            <div class="countdown-box">
                <div class="countdown" data-value="<?php



                                                    // Tampilkan
                                                    echo $waktu_selesai;
                                                    ?>">
                    <div class="time-unit">
                        <div class="time-label">HARI</div>
                        <div class="time-value" id="days">0</div>
                    </div>
                    <div class="separator">:</div>
                    <div class="time-unit">
                        <div class="time-label">JAM</div>
                        <div class="time-value" id="hours">0</div>
                    </div>
                    <div class="separator">:</div>
                    <div class="time-unit">
                        <div class="time-label">MENIT</div>
                        <div class="time-value" id="minutes">0</div>
                    </div>
                    <div class="separator">:</div>
                    <div class="time-unit">
                        <div class="time-label">DETIK</div>
                        <div class="time-value" id="seconds">0</div>
                    </div>
                </div>
                <!-- <div class="countdown-info">
                    <div class="info-date">RABU, 30 MARET 2025</div>
                    <div class="info-text">Berhitung Tanggal 28 Oktobert 2025 14:00 WIB</div>
                </div> -->
            </div>

            <div class="button-container">
                <button class="exit-menu" onclick="handleExit()">Keluar</button>
            </div>
        </div>
    </div>

    <script>
        const targetDate = new Date(document.querySelector('.countdown').getAttribute('data-value')).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;
            console.log(distance)
            if (distance <= 0) {
                window.location.href = '/warga/selesai/pemenang';
            }
            if (distance < 0) {
                document.getElementById('days').textContent = '0';
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = days;
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }

        function handleExit() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                window.location.href = '/assets/php/logoutwarga.php';
            }
        }

        // Update countdown immediately
        updateCountdown();

        // Update countdown every second
        setInterval(updateCountdown, 1000);
    </script>
</body>

</html>