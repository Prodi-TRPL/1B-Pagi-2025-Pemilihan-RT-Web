-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 10 Jan 2026 pada 02.20
-- Versi server: 8.0.44-0ubuntu0.24.04.1
-- Versi PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `klikpilih`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `rt_panitia` varchar(50) NOT NULL,
  `foto_panitia` varchar(255) DEFAULT NULL,
  `sandi` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`rt_panitia`, `foto_panitia`, `sandi`, `email`, `no_hp`) VALUES
('RW', NULL, '$2y$10$MRNU7fUIdWQDA.TP6GLbhuewbb7P2gg74ufRfgAS0L2p/VXw47eIW', 'tutor.programing12@gmail.com', '081378280892');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kandidat`
--

CREATE TABLE `kandidat` (
  `no_kandidat` int NOT NULL,
  `nama_kandidat` varchar(100) NOT NULL,
  `visi` text,
  `misi` text,
  `rt_panitia` varchar(50) DEFAULT NULL,
  `id_sesi` int DEFAULT NULL,
  `foto_kandidat` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data untuk tabel `kandidat`
--



-- --------------------------------------------------------

--
-- Struktur dari tabel `kotak_suara`
--

CREATE TABLE `kotak_suara` (
  `id_suara` int NOT NULL,
  `no_kandidat` int DEFAULT NULL,
  `id_sesi` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data untuk tabel `kotak_suara`
--


-- --------------------------------------------------------

--
-- Struktur dari tabel `sesi_pemilihan`
--

CREATE TABLE `sesi_pemilihan` (
  `id_sesi` int NOT NULL,
  `nama_sesi` varchar(100) NOT NULL,
  `foto_sampul` varchar(255) DEFAULT NULL,
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime NOT NULL,
  `status` enum('Persiapan','Aktif','Selesai','Diarsipkan') DEFAULT 'Persiapan',
  `rt_panitia` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data untuk tabel `sesi_pemilihan`
--


-- --------------------------------------------------------

--
-- Struktur dari tabel `warga`
--

CREATE TABLE `warga` (
  `id_warga` int NOT NULL,
  `nama_warga` varchar(100) NOT NULL,
  `token` varchar(10) DEFAULT NULL,
  `status` enum('Belum Memilih','Sudah Memilih') DEFAULT 'Belum Memilih',
  `rt_panitia` varchar(50) DEFAULT NULL,
  `id_sesi` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data untuk tabel `warga`
--


--
-- Trigger `warga`
--
DELIMITER $$

CREATE TRIGGER generate_token_warga
BEFORE INSERT ON warga
FOR EACH ROW
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE generated_token VARCHAR(10) DEFAULT '';
    DECLARE allowed_chars VARCHAR(36) DEFAULT 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    
    -- Loop sebanyak 10 kali untuk mengambil karakter acak
    WHILE i < 10 DO
        SET generated_token = CONCAT(
            generated_token, 
            SUBSTRING(allowed_chars, FLOOR(1 + RAND() * 36), 1)
        );
        SET i = i + 1;
    END WHILE;

    -- Masukkan hasil generate ke kolom 'token' pada baris baru
    SET NEW.token = generated_token;
END$$

DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`rt_panitia`);

--
-- Indeks untuk tabel `kandidat`
--
ALTER TABLE `kandidat`
  ADD PRIMARY KEY (`no_kandidat`),
  ADD KEY `rt_panitia` (`rt_panitia`),
  ADD KEY `fk_kandidat_sesi` (`id_sesi`);

--
-- Indeks untuk tabel `kotak_suara`
--
ALTER TABLE `kotak_suara`
  ADD PRIMARY KEY (`id_suara`),
  ADD KEY `id_sesi` (`id_sesi`),
  ADD KEY `kotak_suara_ibfk_1` (`no_kandidat`);

--
-- Indeks untuk tabel `sesi_pemilihan`
--
ALTER TABLE `sesi_pemilihan`
  ADD PRIMARY KEY (`id_sesi`),
  ADD KEY `rt_panitia` (`rt_panitia`);

--
-- Indeks untuk tabel `warga`
--
ALTER TABLE `warga`
  ADD PRIMARY KEY (`id_warga`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `rt_panitia` (`rt_panitia`),
  ADD KEY `fk_warga_sesi` (`id_sesi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kandidat`
--
ALTER TABLE `kandidat`
  MODIFY `no_kandidat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT untuk tabel `kotak_suara`
--
ALTER TABLE `kotak_suara`
  MODIFY `id_suara` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT untuk tabel `sesi_pemilihan`
--
ALTER TABLE `sesi_pemilihan`
  MODIFY `id_sesi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT untuk tabel `warga`
--
ALTER TABLE `warga`
  MODIFY `id_warga` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kandidat`
--
ALTER TABLE `kandidat`
  ADD CONSTRAINT `fk_kandidat_sesi` FOREIGN KEY (`id_sesi`) REFERENCES `sesi_pemilihan` (`id_sesi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kandidat_ibfk_1` FOREIGN KEY (`rt_panitia`) REFERENCES `admin` (`rt_panitia`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `kotak_suara`
--
ALTER TABLE `kotak_suara`
  ADD CONSTRAINT `kotak_suara_ibfk_1` FOREIGN KEY (`no_kandidat`) REFERENCES `kandidat` (`no_kandidat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kotak_suara_ibfk_2` FOREIGN KEY (`id_sesi`) REFERENCES `sesi_pemilihan` (`id_sesi`);

--
-- Ketidakleluasaan untuk tabel `sesi_pemilihan`
--
ALTER TABLE `sesi_pemilihan`
  ADD CONSTRAINT `sesi_pemilihan_ibfk_1` FOREIGN KEY (`rt_panitia`) REFERENCES `admin` (`rt_panitia`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `warga`
--
ALTER TABLE `warga`
  ADD CONSTRAINT `fk_warga_sesi` FOREIGN KEY (`id_sesi`) REFERENCES `sesi_pemilihan` (`id_sesi`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `warga_ibfk_1` FOREIGN KEY (`rt_panitia`) REFERENCES `admin` (`rt_panitia`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
