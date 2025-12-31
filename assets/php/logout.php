<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: /admin");
  exit;
}
session_start();

// 1. Hapus Session
$_SESSION = [];
session_unset();
session_destroy();

// 2. Hapus Cookie (Set waktu ke masa lalu)
setcookie('id_panitia', '', time() - 3600, "/admin");
setcookie('key', '', time() - 3600, "/admin");

// 3. Kembalikan ke halaman login
header("Location: ../../aksessebagai");
exit;
?>