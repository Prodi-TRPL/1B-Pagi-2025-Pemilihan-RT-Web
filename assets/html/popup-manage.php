<head>
    <link rel="stylesheet" href="/assets/css/popup/style.css" />
</head>

<body>

    <div class="popupmanage-container">
        <div class="popupmanage" id="popupmanage">
            <h2 id="menu-type-manage">Edit Data Warga</h2>

            <form id="menuFormManage" action="" method="post">
                <label for="nama">
                    Nama
                    <i id="namaError" class="fas fa-exclamation-circle error-icon"></i>
                </label>
                <input type="text" id="nmknddt" placeholder="Nama" name="nama" value="" required />

                <label for="foto">Status</label>
                <select name="status" id="status-warga">
                    <option value="Sudah Memilih">Sudah Memilih</option>
                    <option value="Belum Memilih" selected>Belum Memilih</option>
                </select>
                
                <div class="button-group">
                    <button type="button" class="cancel" id="cancel">Batal</button>
                    <button type="submit" class="confirm" id="ok" name="id" value="">Simpan Perubahan</button>
                </div>
                <input type="hidden" name="id_select_warga" id="idEdit">
                <input type="hidden" name="id_sesi" id="idSesi" value=<?php echo $_GET['id_sesi']?>>
                <input type="hidden" name="rt_panitia" id="idRTPanitia" value='<?php echo $_GET['rt']?>'>
            </form>
        </div>
    </div>
</body>
<script>

</script>