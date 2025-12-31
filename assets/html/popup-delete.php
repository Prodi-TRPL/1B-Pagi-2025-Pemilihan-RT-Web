<head>
    <link rel="stylesheet" href="/assets/css/popup/style.css" />
</head>

<body>
    <div class="popupdelete-container">
        <div class="popupdelete" id="popupdelete">
            <!-- <h2 id="menu-type">Edit Sesi</h2> -->

            <form id="menuForm" action="../../assets/php/hapusDataWarga.php" method="post">
                Apakah anda yakin ingin menghapus?
                <div class="button-group">
                    <button type="button" class="cancel" id="cancel">Batal</button>
                    <button type="submit" class="confirm" id="delete">Konfirmasi Pilihan</button>
                    <input type="hidden" name="id_select_warga" id="idDel">
                    <input type="hidden" name="id_sesi" id="idSesi" value=<?php echo $_GET['id_sesi']?>>
                <input type="hidden" name="rt_panitia" id="idRTPanitia" value='<?php echo $_GET['rt']?>'>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
</script>