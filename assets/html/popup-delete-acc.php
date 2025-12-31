<head>
    <link rel="stylesheet" href="/assets/css/popup/style.css" />
</head>

<body>
    <div class="popupdelete-container">
        <div class="popupdelete" id="popupdelete">
            <!-- <h2 id="menu-type">Edit Sesi</h2> -->

            <form id="menuForm" action="hapusAcc.php" method="post">
                Apakah anda yakin ingin menghapus akun rt?
                <div class="button-group">
                    <button type="button" class="cancel" id="cancel" onclick="closePopupDelete()">Batal</button>
                    <button type="submit" class="confirm" id="delete" onclick="confirmDelete()">Konfirmasi Pilihan</button>
                    <input type="hidden" name="id" id="id" value=<?php echo $_GET['id'] ?>>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
</script>