<?php
include '../config/koneksi.php';

if (isset($_POST['daftar'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $password = $_POST['password'];

    // 1. Enkripsi Password (WAJIB!)
    // Kita ubah "rahasia123" jadi "$2y$10$asdfghjkl..."
    $password_safe = password_hash($password, PASSWORD_DEFAULT);

    // 2. Cek apakah username sudah ada (mencegah duplikat)
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        $error = "Username sudah terdaftar, cari yang lain!";
    } else {
        // 3. Masukkan ke Database
        $query = "INSERT INTO users (username, password, nama_lengkap) 
                  VALUES ('$username', '$password_safe', '$nama')";
        
        if (mysqli_query($koneksi, $query)) {
            header("Location: login.php?pesan=daftar_berhasil");
            exit();
        } else {
            $error = "Gagal mendaftar: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Akun Baru</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="card-auth">
        <h2>📝 Daftar Akun Kasir</h2>
        <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="POST">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Username" required>
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn btn-primary" type="submit" name="daftar">Buat Akun</button>
        </form>
        <p style="text-align:center; font-size: 14px;">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>