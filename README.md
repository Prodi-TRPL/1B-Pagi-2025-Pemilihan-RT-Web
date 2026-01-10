# KlikPilih — Aplikasi Pemilihan Ketua RT Berbasis Web

**KlikPilih** adalah sistem pemungutan suara elektronik (e-voting) berbasis web yang dirancang untuk mendukung proses pemilihan Ketua RT secara **efisien, aman, dan transparan**. Aplikasi ini membantu panitia dalam mengelola data pemilih, kandidat, serta jadwal pemungutan suara, dengan perhitungan hasil yang dilakukan secara otomatis dan real-time.

Setiap pemilih diberikan **token unik** yang hanya dapat digunakan satu kali, sehingga integritas dan keabsahan suara tetap terjaga.

---

## 👥 Tim & Role

<div style="display: flex; flex-wrap: wrap; gap: 12px;">
  <div style="border:1px solid #ddd; border-radius:8px; padding:10px 14px;">
    <strong>Fawwaz Khairiy Wahid</strong><br>
    <span style="color:#555;">Backend Developer</span>
  </div>
  <div style="border:1px solid #ddd; border-radius:8px; padding:10px 14px;">
    <strong>Muhammad Daffa Choir</strong><br>
    <span style="color:#555;">Business Analyst</span>
  </div>
  <div style="border:1px solid #ddd; border-radius:8px; padding:10px 14px;">
    <strong>Muhammad Zadeq</strong><br>
    <span style="color:#555;">Business Analyst</span>
  </div>
  <div style="border:1px solid #ddd; border-radius:8px; padding:10px 14px;">
    <strong>Marsya Zainur Aminarti</strong><br>
    <span style="color:#555;">Frontend Developer</span>
  </div>
  <div style="border:1px solid #ddd; border-radius:8px; padding:10px 14px;">
    <strong>Syera Mardhatillah Deaz</strong><br>
    <span style="color:#555;">Frontend Developer</span>
  </div>
  <div style="border:1px solid #ddd; border-radius:8px; padding:10px 14px;">
    <strong>Maisha Adila Zahra</strong><br>
    <span style="color:#555;">UI / UX Designer</span>
  </div>
</div>

---

## ⚙️ System Requirements

- **PHP** versi 8.3 atau lebih baru
- **MySQL / MariaDB**
- Web Server (Apache / Nginx / PHP Built-in Server)

---

## 🚀 Cara Instalasi

### 1. Clone Repository

Jalankan perintah berikut untuk meng-clone repository:

```bash
git clone https://github.com/Prodi-TRPL/1B-Pagi-2025-Pemilihan-RT-Web.git klikpilih
```

### 2. Konfigurasi Koneksi Database

Buka file berikut:

```
backend/koneksi.php
```

Lalu sesuaikan konfigurasi koneksi database:

```php
<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "nama_database";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
```

### 3. Import Database

Import file database ke MySQL:

```bash
mysql -u root -p nama_database < klikpilih.sql
```

### 4. Jalankan Server

Gunakan PHP built-in server:

```bash
php server.php
```

### 5. Akses Aplikasi

Buka browser dan akses:

```
http://localhost/klikpilih
```

---

## 📌 Catatan

- Pastikan ekstensi PHP yang dibutuhkan telah aktif (mysqli).
- Token pemilih bersifat **sekali pakai** dan tidak dapat digunakan ulang.
- Sistem ini dirancang untuk kebutuhan pembelajaran dan implementasi skala lingkungan RT.

---

© 2025 — KlikPilih. All rights reserved.

