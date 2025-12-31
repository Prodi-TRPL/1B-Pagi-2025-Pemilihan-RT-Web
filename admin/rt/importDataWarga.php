<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: /admin");
    exit;
}
session_start();
header('Content-Type: application/json');
// 1. Panggil Autoload dari Composer
require '../../vendor/autoload.php';
// 2. Gunakan Namespace PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

// Koneksi Database
require $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';
if (isset($_FILES['file_warga'])) {

    // Ambil detail file
    $fileTmpPath = $_FILES['file_warga']['tmp_name'];
    $fileName    = $_FILES['file_warga']['name'];
    $fileTMPName = $_FILES['file_warga']['tmp_name'];
    $fileSize    = $_FILES['file_warga']['size'];
    $fileType    = $_FILES['file_warga']['type'];

    // Validasi ekstensi file (harus xlsx atau xls)
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    if ($_FILES['file_warga']['size'] > 0) {
        if ($fileExtension === 'csv') {
            // Buka file CSV mode Read (r)
            $file = fopen($fileTMPName, "r");

            // Lewati baris pertama jika itu adalah JUDUL KOLOM (Header)
            // Hapus baris di bawah ini jika CSV kamu tidak punya judul kolom
            fgetcsv($file, 10000, ",");
            $rt_panitia = $_POST['rt_panitia'];
            $id_sesi = $_POST['id_sesi'];

            // Looping membaca baris per baris
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {

                // Sesuaikan urutan array dengan urutan kolom di CSV kamu
                // Contoh CSV: Nama, Status
                $nama_warga = $column[0];
                $status     = $column[1];

                // Hardcode RT Panitia (atau ambil dari Session login admin)
                // $rt_panitia = 'RT001'; 

                // Query Insert
                // Ingat: ID Warga (Auto Increment) & Token (Trigger) tidak perlu diisi manual
                $sql = "INSERT INTO warga (nama_warga, status, rt_panitia, id_sesi) 
                    VALUES (?,?,?,?)";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $nama_warga, $status, $rt_panitia, $id_sesi);
                $stmt->execute();
                $result = $stmt->get_result();
            }

            // Tutup file
            fclose($file);

            echo "Sukses impor data";
        } elseif ($fileExtension === 'xlsx' || $fileExtension === 'xls') {

            try {
                // 3. Load file Excel menggunakan IOFactory
                $spreadsheet = IOFactory::load($fileTmpPath);
                // Ambil sheet aktif (sheet pertama)
                $sheetData = $spreadsheet->getActiveSheet()->toArray();

                $rt_panitia = $_POST['rt_panitia'];
                $id_sesi    = $_POST['id_sesi'];
                

                $sukses_count = 0;
                $gagal_count  = 0;

                // Siapkan Prepared Statement di luar loop agar lebih cepat
                $sql = "INSERT INTO warga (nama_warga, status, rt_panitia, id_sesi) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                // 4. Looping data array dari Excel
                // $key adalah index baris (0, 1, 2...), $row adalah array datanya
                foreach ($sheetData as $key => $row) {

                    // Lewati baris pertama (Header Judul)
                    // Asumsi baris 0 adalah: "Nama Warga", "Status"
                    if ($key == 0) {
                        continue;
                    }

                    // Ambil data kolom A (index 0) dan B (index 1)
                    $nama_warga = $row[0];
                    $status     = $row[1];

                    // Pastikan nama tidak kosong sebelum insert
                    if (!empty($nama_warga)) {
                        $stmt->bind_param("sssi", $nama_warga, $status, $rt_panitia, $id_sesi);

                        if ($stmt->execute()) {
                            $sukses_count++;
                        } else {
                            $gagal_count++;
                        }
                    }
                }

                echo json_encode([
                    "status" => "success",
                    "message" => "Import selesai. Sukses: $sukses_count, Gagal: $gagal_count"
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Gagal membaca file Excel: " . $e->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Upload gagal. Pastikan format file .xlsx atau .xls"
            ]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Tidak ada file yang diupload"]);
    }
}
