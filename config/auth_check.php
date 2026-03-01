<?php
session_start();

// Logika: Jika tidak ada session id, tendang ke login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php?pesan=belum_login");
    exit();
}
?>