<?php
// --- 1. Setup & Koneksi ---
ob_start();
require('fpdf.php');
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
session_start();
$id_param_saved = htmlspecialchars($_GET['id_sesi']);
$rt_param_saved = htmlspecialchars($_GET['rt']);    
$query = "SELECT * FROM sesi_pemilihan where rt_panitia = ? AND id_sesi = ? ORDER BY id_sesi ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $rt_param_saved, $id_param_saved);
$stmt->execute();
$result = $stmt->get_result();
if ($_SESSION['rt_panitia'] != "RW") {
    if ($_SESSION['rt_panitia'] != $rt_param_saved) {
        header("Location: ../admin/rt/index.php?rt=" . $_SESSION['saved_rt_panitia_param'] . "&id_sesi=" . $_SESSION['saved_id_sesi_param']);
        exit;
    }
}
if (mysqli_num_rows($result) == 0) {
    header("Location: ../admin/rt/index.php?rt=" . $_SESSION['saved_rt_panitia_param'] . "&id_sesi=" . $_SESSION['saved_id_sesi_param']);
    exit;
}
$_SESSION['saved_rt_panitia_param'] = $rt_param_saved;
$_SESSION['saved_id_sesi_param'] = $id_param_saved;

// --- 2. Ambil Parameter Input ---
// Asumsi ID Sesi diambil dari URL, misal: cetak_hasil.php?id_sesi=1
$idSesi = isset($_GET['id_sesi']) ? $_GET['id_sesi'] : 1;

// --- 3. Ambil Informasi Sesi (Untuk Judul Laporan) ---
// Kita perlu tahu nama sesi dan RT berapa untuk ditampilkan di Header PDF
$sql_info = "SELECT nama_sesi, rt_panitia, waktu_mulai, waktu_selesai FROM sesi_pemilihan WHERE rt_panitia = ? AND id_sesi = ?";
$stmt_info = $conn->prepare($sql_info);
$stmt_info->bind_param("si", $rt_param_saved, $id_param_saved);
$stmt_info->execute();
$res_info = $stmt_info->get_result();

if ($res_info->num_rows == 0) {
    die("Data sesi tidak ditemukan.");
}
$infoSesi = $res_info->fetch_assoc();


// --- 4. Ambil Data Suara (QUERY DARI ANDA) ---
$sql = "SELECT 
            k.no_kandidat, 
            k.nama_kandidat, 
            COUNT(ks.no_kandidat) as total_suara
        FROM kandidat k
        LEFT JOIN kotak_suara ks 
            ON k.no_kandidat = ks.no_kandidat 
            AND ks.id_sesi = k.id_sesi
        WHERE k.id_sesi = ?
        GROUP BY k.no_kandidat
        ORDER BY total_suara DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idSesi);
$stmt->execute();
$result = $stmt->get_result();

// --- 5. Proses Data di PHP (Hitung Total Global) ---
$data_laporan = [];
$total_seluruh_suara = 0;

while ($row = $result->fetch_assoc()) {
    $data_laporan[] = $row; // Simpan baris ke array
    $total_seluruh_suara += $row['total_suara']; // Akumulasi total
}

// Cegah error division by zero jika belum ada suara
if ($total_seluruh_suara == 0) $total_seluruh_suara = 1;


// --- 6. Generate PDF ---
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// -- HEADER LAPORAN --
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'BERITA ACARA HASIL PEMILIHAN KETUA RT', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 7, 'RUKUN TETANGGA (RT) ' . $infoSesi['rt_panitia'], 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetLineWidth(0.5);
$pdf->Line(10, 38, 200, 38); // Garis bawah header
$pdf->Ln(10);

// -- INFO SINGKAT --
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(40, 6, 'Waktu Pelaksanaan', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
// Format tanggal Indonesia (Opsional, sesuaikan kebutuhan)
$tanggal = date('d-m-Y', strtotime($infoSesi['waktu_mulai']));
$pdf->Cell(0, 6, $tanggal, 0, 1);

$pdf->Cell(40, 6, 'Total Suara Masuk', 0, 0);
$pdf->Cell(5, 6, ':', 0, 0);
// Tampilkan total yang kita hitung di langkah 5
// Jika data aslinya 0, kita tampilkan 0 (karena tadi di-set 1 hanya untuk pembagi)
$tampil_total = ($total_seluruh_suara == 1 && empty($data_laporan)) ? 0 : $total_seluruh_suara;
if (!empty($data_laporan) && $data_laporan[0]['total_suara'] == 0 && count($data_laporan) == 1) $tampil_total = 0; // Edge case fix
// Atau sederhananya ambil sum dari array lagi jika ragu, tapi logika di atas sudah cukup.
// Koreksi logika tampilan:
$real_total = 0;
foreach ($data_laporan as $d) $real_total += $d['total_suara'];
$pdf->Cell(0, 6, $real_total . ' Suara', 0, 1);

$pdf->Ln(5);

// -- TABEL HASIL --
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230); // Abu-abu muda

// Header Tabel
$pdf->Cell(15, 10, 'No', 1, 0, 'C', true);
$pdf->Cell(85, 10, 'Nama Kandidat', 1, 0, 'C', true);
$pdf->Cell(45, 10, 'Perolehan Suara', 1, 0, 'C', true);
$pdf->Cell(45, 10, 'Persentase', 1, 1, 'C', true);

// Isi Tabel
$pdf->SetFont('Arial', '', 10);
$nomor = 0;

if (count($data_laporan) > 0) {
    foreach ($data_laporan as $row) {
        $nomor++;
        $suara = $row['total_suara'];

        // Hitung Persen
        $persen = ($suara / $total_seluruh_suara) * 100;

        // Cetak
        $pdf->Cell(15, 10, $nomor, 1, 0, 'C'); // Pakai no_kandidat dari DB
        $pdf->Cell(85, 10, ' ' . $row['nama_kandidat'], 1, 0, 'L');
        $pdf->Cell(45, 10, $suara, 1, 0, 'C');
        $pdf->Cell(45, 10, number_format($persen, 2) . ' %', 1, 1, 'C');
    }
} else {
    $pdf->Cell(190, 10, 'Belum ada data kandidat atau suara masuk.', 1, 1, 'C');
}


// Output
ob_end_clean();
$pdf->Output('I', 'Hasil_Pemilihan_RT.pdf');

// Tutup Koneksi
$stmt->close();
$stmt_info->close();
$conn->close();
