<?php
session_start(); // MEMULAI INGATAN SERVER
include '../config/koneksi.php';

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($koneksi, $_POST['username']);
    $pass = $_POST['password'];

    // 1. Cari user berdasarkan username
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$user'");
    $data  = mysqli_fetch_assoc($query);

    if ($data) {
        // 2. Cek apakah password cocok dengan hash di DB
        if (password_verify($pass, $data['password'])) {
            // 3. Jika cocok, buat Session
            $_SESSION['admin_id'] = $data['id'];
            $_SESSION['nama']     = $data['nama_lengkap'];
            
            header("Location: ../index.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="card-auth">
        <h2>🔒 Login Kasir</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Username" required>
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn btn-primary" type="submit" name="login">Masuk</button>
            <a href="register.php" style="display:block; text-align:center; margin-top:10px; color:#666;">Daftar</a>
        </form>
    </div>
</body>
</html>