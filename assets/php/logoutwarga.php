<?php
session_start();
if (!isset($_SESSION["login_warga"])) {
  header("Location: /warga");
  exit;
}
session_start();

// 1. Hapus Session
$_SESSION = [];
session_unset();
session_destroy();

// 2. Hapus Cookie (Set waktu ke masa lalu)
setcookie('id_warga', '', time() - 3600, "/warga");
setcookie('key_warga', '', time() - 3600, "/warga");

// 3. Kembalikan ke halaman login
header("Location: ../../aksessebagai");
exit;
?>