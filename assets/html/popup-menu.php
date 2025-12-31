<head>
    <link rel="stylesheet" href="/assets/css/popup/style.css" />
</head>

<body>
    <div class="popupmenu-container">
        <div class="popupmenu" id="popupmenu">
            <h2 id="menu-type">Edit Sesi</h2>

            <form id="menuFormMenu" action="" method="post" enctype="multipart/form-data">
                <label for="nama">
                    Nama
                    <i id="namaError" class="fas fa-exclamation-circle error-icon"></i>
                </label>
                <input type="text" id="nmssi" name="nama" placeholder="Nama" required />

                <label for="foto_sampul">Foto</label>
                <div class="photo-upload" onclick="this.querySelector('input[type=file]').click()">
                    <img  id="preview_foto_sampul" class="preview-img">

                    <div class="icon-overlay">
                        <svg class="camera-icon" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            <path d="M0 0h24v24H0z" fill="none" />
                            <circle cx="12" cy="10" r="3.2" />
                            <path
                                d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z" />
                        </svg>
                        <span>Klik untuk Upload</span>
                    </div>
                    <input type="file" id="foto_sampul" name="foto_sampul" accept="image/*"
                        style="display:none;">
                </div>
                <div class="rt-select">
                    <?php
                    $query = "SELECT * FROM admin";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($_SESSION['rt_panitia'] == 'RW') {
                        echo '<label for="rt_panitia">RT</label>';
                        echo '<select id="rt_panitia" name="rt_panitia">';
                        if (mysqli_num_rows($result) > 0) {
                            // 4. Looping: Mengulang kode HTML sebanyak jumlah baris data (i kali)
                            while ($row = mysqli_fetch_assoc($result)) {

                                // Menampung data database ke variabel agar mudah dibaca
                                $rtrw = $row['rt_panitia'];

                                // Tentukan warna tombol status (Opsional: Agar tampilan lebih bagus)
                                // Jika status 'Sudah Memilih' tombol jadi hijau, jika belum jadi kuning/merah
                    ?>

                                <option value=<?php echo "'$rtrw'"; ?> selected><?php echo "$rtrw"; ?></option>

                    <?php
                            } // Akhir dari loop while
                        }
                        echo '</select>';
                    } else {
                        $rtrw = $_SESSION['rt_panitia'];
                        echo '<input type="hidden" id="rt_panitia" name="rt_panitia" value="' . $rtrw . '">';
                    }
                    $stmt->close();
                    ?>
                </div>
                <label>Waktu Pemilihan</label>
                <div class="time-group">
                    <input type="date" id="tanggal1" value="2025-10-01" name="tgl_mulai" />
                    <input type="time" id="waktu1" name="jam_mulai" value="00:00" />
                </div>
                <div class="time-group">
                    <input type="date" id="tanggal2" value="2025-10-02" name="tgl_selesai" />
                    <input type="time" id="waktu2" name="jam_selesai" value="00:00" />
                </div>

                <div class="button-group">
                    <button type="button" class="cancel" id="cancel">Batal</button>
                    <button type="submit" class="confirm" id="ok" name="id" value="">Konfirmasi</button>
                </div>
                <input type="hidden" name="id_select_sesi_pemilihan" id="idEdit">
            </form>
        </div>
    </div>
</body>
<script>
</script>