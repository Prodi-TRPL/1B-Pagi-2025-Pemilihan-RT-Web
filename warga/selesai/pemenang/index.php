<?php
session_start();
if (!isset($_SESSION["login_warga"])) {
    header("Location: /warga");
    exit;
}
$title_web = "Pemenang Pemilihan";
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
$id_warga = $_SESSION['id_warga'];
// --- Langkah 1: Cari ID Sesi di tabel Warga ---
$stmt = $conn->prepare("SELECT id_sesi FROM warga WHERE id_warga = ?");
$stmt->bind_param("i", $id_warga); // "s" = string
$stmt->execute();
$result = $stmt->get_result();
$row_warga = $result->fetch_assoc();
$stmt->close();
$id_sesi = $row_warga['id_sesi'] ?? null;
if ($id_sesi) {
    $stmt = $conn->prepare("SELECT status, waktu_selesai FROM sesi_pemilihan WHERE id_sesi = ?");
    $stmt->bind_param("i", $id_sesi); // "i" = integer
    $stmt->execute();
    $result_sesi = $stmt->get_result();
    $row_sesi = $result_sesi->fetch_assoc();
    $status_sesi = $row_sesi['status'] ?? null;
    $stmt->close();
}

if ($status_sesi != 'Selesai') {
    header("Location: /warga/selesai/index.php");
    exit;
}
$stmt = $conn->prepare("SELECT k.nama_kandidat, COUNT(ks.no_kandidat) as total_suara
          FROM kotak_suara ks
          JOIN kandidat k ON ks.no_kandidat = k.no_kandidat
          WHERE ks.id_sesi = ? 
          GROUP BY k.no_kandidat
          ORDER BY total_suara DESC");
$stmt->bind_param("i", $id_sesi);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// 2. Masukkan semua hasil ke dalam Array agar mudah dicek
$data_suara = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data_suara[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title_web ?></title>
    <link rel="icon" type="image/x-icon" href="/assets/img/logo-2.ico">
    <link rel="stylesheet" href="/assets/css/pemenang/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/header.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/sidebar.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/assets/html/navbar.php'; ?>
    <main>
        <div class="container">
            <?php
            // 3. Logika Penentuan Pemenang
            if (empty($data_suara)) {
                // KASUS A: Belum ada suara masuk sama sekali
                echo "<h1>Belum ada suara masuk.</h1>";
            } else {
                // KASUS C: Ada lebih dari 1 calon, kita bandingkan Juara 1 dan 2
                $juara_1 = $data_suara[0]; // Baris pertama (Suara terbanyak)
                $juara_2 = $data_suara[1]; // Baris kedua

                // Cek apakah suara mereka SAMA?
                if ($juara_1['total_suara'] == $juara_2['total_suara']) {
                    // --- HASIL SERI ---
                    echo "<h1 style='color: orange;'>HASIL SERI (DRAW)!</h1>";
                    echo "<h2>Kandidat <b>" . $juara_1['nama_kandidat'] . "</b> dan <b>" . $juara_2['nama_kandidat'] . "</b>";
                    echo " sama-sama memperoleh <b>" . $juara_1['total_suara'] . "</b> suara.</h2>";
                } else {
                    // --- ADA PEMENANG ---
                    echo "<h1 style='color: green;'>PEMENANGNYA: <br>" . $juara_1['nama_kandidat'] . "</h1>";
                    echo "<h2>Total: <b>" . $juara_1['total_suara'] . "</b> Suara</h2>";
                    echo "<h3>Unggul " . ($juara_1['total_suara'] - $juara_2['total_suara']) . " suara dari " . $juara_2['nama_kandidat'] . ".</h3>";
                }
            }
            ?>
            <div class="chart-container">
                <div class="chart">
                    <h2>Hasil Pemilihan Ketua RT</h2>
                    <canvas id="myChart" width="250" height="200"></canvas>
                </div>
            </div>
        </div>
    </main>
    <div class="button-container">
        <button class="exit-menu" onclick="handleExit()">Keluar</button>
        <button class="confirm" onclick="generate_rekapan()">Rekapan</button>
    </div>
</body>
<?php
$juara_1_suara = $data_suara[0]['total_suara'];
$juara_1_nama = $data_suara[0]['nama_kandidat'];
$juara_2_suara = $data_suara[1]['total_suara'];
$juara_2_nama = $data_suara[1]['nama_kandidat'];
echo "
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['$juara_1_nama', '$juara_2_nama'],
            datasets: [{
                label: '# of Votes',
                data: [$juara_1_suara, $juara_2_suara],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 // Agar sumbu Y angka bulat (bukan 1.5 suara)
                    }
                }
            }
        }
    });
</script>

";

?>
<script>
    function handleExit() {
        window.location.href = '/assets/php/logoutwarga.php';
    }

    function generate_rekapan() {
        window.location.href = '/fpdf/cetakan_rekapan.php?id_sesi=<?php
                                                                    // 1. Siapkan Query (Template)
                                                                    $stmt = $conn->prepare("SELECT id_sesi FROM warga WHERE id_warga = ?");

                                                                    // 2. Masukkan Data (Bind) - "s" = string
                                                                    $stmt->bind_param("i", $id_warga);

                                                                    // 3. Jalankan
                                                                    $stmt->execute();

                                                                    // 4. Ambil Hasil
                                                                    $result = $stmt->get_result();
                                                                    $row = $result->fetch_assoc();

                                                                    // 5. Tampilkan (Echo)
                                                                    // Simbol '??' berguna jika data tidak ditemukan, dia akan mencetak string kosong '' (atau bisa diganti 'Data Tidak Ada')
                                                                    echo $row_warga['id_sesi'] ?? '';

                                                                    // 6. Bersihkan
                                                                    $stmt->close(); ?>';
    }
</script>

</html>