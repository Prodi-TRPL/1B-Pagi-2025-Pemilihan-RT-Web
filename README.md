# KlikPilih — Aplikasi Pemilihan Ketua RT Berbasis Web

**KlikPilih** adalah sistem pemungutan suara elektronik (e-voting) berbasis web yang dirancang untuk mendukung proses pemilihan Ketua RT secara **efisien, aman, dan transparan**. Aplikasi ini membantu panitia dalam mengelola data pemilih, kandidat, serta jadwal pemungutan suara, dengan perhitungan hasil yang dilakukan secara otomatis dan real-time.

Setiap pemilih diberikan **token unik** yang hanya dapat digunakan satu kali, sehingga integritas dan keabsahan suara tetap terjaga.

---

## 👥 Tim & Role

<p style="color:#c9d1d9;">
  Tim yang telah berkontribusi dalam pengembangan projek ini:
</p>

<table align="center">
  <tr>
    <td align="center">
      <a href="https://github.com/USERNAME_GITHUB">
        <img src="https://github.com/USERNAME_GITHUB.png" width="100px;" alt="Fawwaz Khairiy Wahid"/><br />
        <sub><b>Fawwaz Khairiy Wahid<br>(Backend Developer)</b></sub>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/USERNAME_GITHUB">
        <img src="https://github.com/USERNAME_GITHUB.png" width="100px;" alt="Muhammad Daffa Choir"/><br />
        <sub><b>Muhammad Daffa Choir<br>(Business Analyst)</b></sub>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/USERNAME_GITHUB">
        <img src="https://github.com/USERNAME_GITHUB.png" width="100px;" alt="Muhammad Zadeq"/><br />
        <sub><b>Muhammad Zadeq<br>(Business Analyst)</b></sub>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/USERNAME_GITHUB">
        <img src="https://github.com/USERNAME_GITHUB.png" width="100px;" alt="Marsya Zainur Aminarti"/><br />
        <sub><b>Marsya Zainur Aminarti<br>(Frontend Developer)</b></sub>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/USERNAME_GITHUB">
        <img src="https://github.com/USERNAME_GITHUB.png" width="100px;" alt="Syera Mardhatillah Deaz"/><br />
        <sub><b>Syera Mardhatillah Deaz<br>(Frontend Developer)</b></sub>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/USERNAME_GITHUB">
        <img src="https://github.com/USERNAME_GITHUB.png" width="100px;" alt="Maisha Adila Zahra"/><br />
        <sub><b>Maisha Adila Zahra<br>(UI / UX Designer)</b></sub>
      </a>
    </td>
  </tr>
</table>

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
mysql -u root -p nama_database < klikpilih_db.sql
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
- Login dengan SuperAdmin RW dengan kata sandi 123 (harap diganti kata sandinya setelah berhasil login)
- Token pemilih bersifat **sekali pakai** dan tidak dapat digunakan ulang.
- Sistem ini dirancang untuk kebutuhan pembelajaran dan implementasi skala lingkungan RT.

---

© 2025 — KlikPilih. All rights reserved.

