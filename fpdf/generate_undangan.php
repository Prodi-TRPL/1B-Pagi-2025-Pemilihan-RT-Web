<?php
// --- 1. Memuat Library FPDF ---
// Pastikan file fpdf.php ada di folder yang sama dengan script ini
ob_start();
require('fpdf.php');
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
session_start();

// --- 2. Konfigurasi Database ---
// Membuat koneksi (Pastikan variabel $host, $user, $pass, $db sudah didefinisikan sebelumnya di file config Anda atau di sini)
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
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
// --- 3. Filter Data (Input Parameter) ---
$filter_id_sesi = $_GET['id_sesi'];       // Contoh: Generate untuk Sesi 1
$filter_rt_panitia = $_GET['rt']; // Contoh: Generate untuk RT 05

// --- 4. Mengambil Data dari Database ---
$sql = "SELECT nama_warga, token 
        FROM warga 
        WHERE id_sesi = ? AND rt_panitia = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $filter_id_sesi, $filter_rt_panitia);
$stmt->execute();
$result = $stmt->get_result();

// Cek jika data ditemukan
if ($result->num_rows == 0) {
    die("Tidak ada data warga ditemukan untuk ID Sesi: $filter_id_sesi dan RT Panitia: $filter_rt_panitia");
}

// --- 5. Membuat PDF dengan FPDF ---
$pdf = new FPDF('P', 'mm', 'A4');

// Loop melalui setiap baris data warga
while ($row = $result->fetch_assoc()) {
    $nama = $row['nama_warga'];
    $token = $row['token'];

    // Tambahkan halaman baru
    $pdf->AddPage();

    // --- Desain Undangan ---

    // Header / Judul
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(0, 15, 'UNDANGAN PEMILIHAN', 0, 1, 'C');

    // Garis pemisah
    $pdf->SetLineWidth(0.5);
    $pdf->Line(10, 25, 200, 25);
    $pdf->Ln(10);

    // Konten Pembuka
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Kepada Yth. Bapak/Ibu/Saudara/i,', 0, 1, 'L');

    // Nama Warga
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0, 50, 150);
    $pdf->Cell(0, 15, ($nama), 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0);

    $pdf->Ln(5);

    // --- BAGIAN YANG DIUBAH (DETAIL UNDANGAN) ---
    $pdf->SetFont('Arial', '', 12);

    // Menyusun teks undangan dengan format baris baru (\n) agar rapi
    $isi_undangan = "Kami mengundang Anda untuk hadir dan memberikan hak suara dalam acara UjiCoba Pemilihan Ketua RT melalui aplikasi berbasis web KlikPilih yang akan dilaksanakan pada:\n\n" .
        "Hari                    : Jumat\n" .
        "Waktu                : Saat kelompok 3 presentasi s.d. Selesai\n" .
        "Tempat              : Ruang TA 10.3\n" .
        "Tautan Website : polibatam.id/KlikPilih\n\n" .
        "Mohon gunakan token di bawah ini untuk akses pemilihan. Atas perhatian dan kehadiran Anda, kami ucapkan terima kasih.";

    // MultiCell digunakan agar teks bisa wrap (turun baris) otomatis dan membaca karakter \n
    $pdf->MultiCell(0, 7, $isi_undangan, 0, 'L');

    $pdf->Ln(10);

    // Kotak Token
    $pdf->SetFillColor(230, 230, 230);
    $pdf->SetFont('Courier', 'B', 18);
    $pdf->Cell(0, 20, "TOKEN: " . $token, 1, 1, 'C', true);

    $pdf->Ln(20);

    // Footer
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, 'Suara Anda menentukan masa depan RT kita.', 0, 1, 'C');

    // Detail sesi di pojok bawah
    $pdf->SetY(-30);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, "Detail: Sesi $filter_id_sesi | RT $filter_rt_panitia", 0, 1, 'R');
}

// Nama file output
$nama_pdf = 'Undangan ' . $_GET['rt_panitia'] . ' ID Sesi ' . $_GET['id_sesi'] . '.pdf';

// Tutup koneksi
$stmt->close();
$conn->close();

// --- 6. Output PDF ---
ob_end_clean();
$pdf->Output('I', $nama_pdf);
