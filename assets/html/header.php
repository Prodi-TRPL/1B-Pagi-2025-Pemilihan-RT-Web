<head>
    <link rel="stylesheet" href="/assets/css/header/style.css" />
    
</head>

<body>
    <header class="top-header">
        <div class="left-header">
            <img src="/assets/img/logo-2.png" alt="RW Icon" class="rw-icon" id="rwToggle" />
            <?php
            if (isset($_SESSION["login"]) && str_starts_with($_SERVER['REQUEST_URI'], '/admin') ) {
                echo '<span class="rw-text">' . htmlspecialchars($_SESSION['rt_panitia'], ENT_QUOTES, 'UTF-8') . '</span>';
            } elseif (isset($_SESSION["login_warga"]) && str_starts_with($_SERVER['REQUEST_URI'], '/warga')) {
                echo '<span class="rw-text">' . htmlspecialchars($_SESSION['nama_warga'], ENT_QUOTES, 'UTF-8') . '</span>';
            }
            ?>
        </div>
    </header>
</body>