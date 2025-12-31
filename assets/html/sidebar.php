

<head>
    <link rel="stylesheet" href="/assets/css/sidebar/style.css" />
</head>

<body>
    <aside class="sidebar hide" id="sidebar">
        <div class="sidebar-container">
            <div class="sidebar-header">
                <div class="close-btn" id="closeBtn">→</div>
                <div class="profile-icon"></div>
            </div>
            <div class="menu-item beranda" id="dashboard-menu" onclick="navigateTo('beranda')">Beranda</div>
            <div class="menu-item sunting" id="edit-menu" onclick="navigateTo('sunting')">Sunting Profil</div>
            <?php
            if ($_SESSION['rt_panitia'] == 'RW') {
                echo '<div class="menu-item superadmin" id="superadmin-menu" onclick="navigateTo(' . "'superadmin'" . ')">Kelola Admin</div>';
            }
            ?>
            <div class="menu-item keluar" id="exit-menu">Keluar</div>
        </div>
    </aside>
</body>
<script>
    const rwToggle = document.getElementById('rwToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarContainer = document.querySelector('.sidebar-container');
    rwToggle.addEventListener('click', () => {
        if (<?php echo isset($_SESSION['login']) ? 1 : 0;?> == 1) {
            sidebar.classList.toggle('show');
            sidebar.classList.remove('hide');
        }
    });
    const closeBtn = document.getElementById('closeBtn');
    // const content = document.getElementById('content');

    closeBtn.addEventListener('click', () => {
        sidebar.classList.remove('show');
        sidebar.classList.toggle('hide');
    });


    function navigateTo(page) {
        switch (page) {
            case 'beranda':
                window.location.href = '/admin/dashboard/';
                break;
            case 'superadmin':
                window.location.href = '/admin/kelolaadmin/';
                break;
            case 'sunting':
                window.location.href = '/admin/suntingprofil/';
                break;
        }
    }

    // Fitur Tambahan: Menutup menu jika user klik di luar menu
    // window.onclick = function(event) {
    //     // alert (event.target.closest('.dots'))

    // }
    switch (document.title) {
        case "Beranda":
            document.getElementById("dashboard-menu").classList.add('active-menu');
            document.getElementById("dashboard-menu").classList.remove('beranda');
            break;
        case "Kelola Admin":
            document.getElementById("superadmin-menu").classList.add('active-menu');
            document.getElementById("superadmin-menu").classList.remove('superadmin');
            break;
        case "Sunting Profil":
            document.getElementById("edit-menu").classList.add('active-menu');
            document.getElementById("edit-menu").classList.remove('sunting');
            break;
    }
</script>