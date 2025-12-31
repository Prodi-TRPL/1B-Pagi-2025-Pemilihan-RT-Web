<?php

include $_SERVER['DOCUMENT_ROOT'] . '/backend/koneksi.php';


if ($title_web == "Sesi Pemilihan") {
    $stmt = $conn->prepare("SELECT nama_sesi FROM sesi_pemilihan WHERE id_sesi = ? AND rt_panitia = ?");
    $stmt->bind_param("is", $_GET['id_sesi'], $_GET['rt']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $nav_text = "<a href='/admin/dashboard/index.php'>Beranda</a> / ". $_GET['rt'] . " / " . $row['nama_sesi'];
} else {
    $nav_text = $title_web;
}

$nav_text;
?>
<head>
    <link rel="stylesheet" href="/assets/css/navbar/style.css" />
</head>
<body>
    <nav class="navbar">
        <div class="nav-left">
            <p class="nav-main" id="nav-main"><?php echo $nav_text ?></p>
            <p class="nav-sub">KlikPilih</p>
        </div>
        <div class="nav-right">
            <!-- <input type="text" placeholder="Cari..." class="search-input" /> -->
        </div>
    </nav>
</body>