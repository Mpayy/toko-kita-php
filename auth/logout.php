<?php
session_start();
session_destroy(); // Menghancurkan semua data session (buang kartu akses)

header("Location: login.php?pesan=logout_berhasil");
exit();
?>