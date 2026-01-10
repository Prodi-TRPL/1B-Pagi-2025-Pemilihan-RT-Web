# KlikPilih — Aplikasi Pemilihan Ketua RT Berbasis Web

**KlikPilih** adalah sistem pemungutan suara elektronik (e-voting) berbasis web yang dirancang untuk mendukung proses pemilihan Ketua RT secara **efisien, aman, dan transparan**. Aplikasi ini membantu panitia dalam mengelola data pemilih, kandidat, serta jadwal pemungutan suara, dengan perhitungan hasil yang dilakukan secara otomatis dan real-time.

Setiap pemilih diberikan **token unik** yang hanya dapat digunakan satu kali, sehingga integritas dan keabsahan suara tetap terjaga.

---

## 👥 Tim & Role

<h2 style="display:flex; align-items:center; gap:8px;">
  👥 Anggota Tim PBL-TRPL104-B4
</h2>

<p style="color:#c9d1d9;">
  Tim yang telah berkontribusi dalam pengembangan projek ini:
</p>

<div style="
  display:flex;
  gap:16px;
  flex-wrap:wrap;
  background:#0d1117;
  padding:16px;
  border-radius:10px;
  border:1px solid #30363d;
">

  <!-- Anggota 1 -->
  <div style="
    width:150px;
    text-align:center;
    background:#161b22;
    border:1px solid #30363d;
    border-radius:10px;
    padding:12px;
  ">
    <img src="https://i0.wp.com/seds.org/wp-content/uploads/2020/02/placeholder.png?fit=1200%2C800&ssl=1&w=640" style="
      width:100%;
      height:140px;
      object-fit:cover;
      border-radius:8px;
    ">
    <p style="margin:10px 0 4px; font-weight:bold; color:#58a6ff;">
      Fawwaz Khairiy Wahid
    </p>
    <p style="margin:0; font-size:13px; color:#8b949e;">
      Backend Developer
    </p>
  </div>

  <!-- Anggota 2 -->
  <div style="
    width:150px;
    text-align:center;
    background:#161b22;
    border:1px solid #30363d;
    border-radius:10px;
    padding:12px;
  ">
    <img src="https://i0.wp.com/seds.org/wp-content/uploads/2020/02/placeholder.png?fit=1200%2C800&ssl=1&w=640" style="
      width:100%;
      height:140px;
      object-fit:cover;
      border-radius:8px;
    ">
    <p style="margin:10px 0 4px; font-weight:bold; color:#58a6ff;">
      Marsya Zainur Aminarti
    </p>
    <p style="margin:0; font-size:13px; color:#8b949e;">
      Frontend Developer
    </p>
  </div>

  <!-- Anggota 3 -->
  <div style="
    width:150px;
    text-align:center;
    background:#161b22;
    border:1px solid #30363d;
    border-radius:10px;
    padding:12px;
  ">
    <img src="https://i0.wp.com/seds.org/wp-content/uploads/2020/02/placeholder.png?fit=1200%2C800&ssl=1&w=640" style="
      width:100%;
      height:140px;
      object-fit:cover;
      border-radius:8px;
    ">
    <p style="margin:10px 0 4px; font-weight:bold; color:#58a6ff;">
      Maisha Adila Zahra
    </p>
    <p style="margin:0; font-size:13px; color:#8b949e;">
      UI / UX Designer
    </p>
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

