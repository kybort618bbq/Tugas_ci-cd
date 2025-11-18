<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Balik ke halaman awal
header("Location: index.html");
exit();
?>
