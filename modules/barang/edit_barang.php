<?php
require '../../config/auth_check.php';
include '../../config/koneksi.php';

// --- BAGIAN 1: AMBIL DATA LAMA ---
$id = $_GET['id'];
$ambil_data = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = '$id'");
$data = mysqli_fetch_assoc($ambil_data);

// Jika ID tidak ditemukan di database
if (!$data) {
    die("Data tidak ditemukan!");
}

// --- BAGIAN 2: PROSES SIMPAN PERUBAHAN ---
if (isset($_POST['update'])) {
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $stok  = mysqli_real_escape_string($koneksi, $_POST['stok']);

    // Logika: "Ubah data produk, SET nilai baru, HANYA untuk ID ini"
    $query_update = "UPDATE produk SET 
                    nama_barang = '$nama', 
                    harga = '$harga', 
                    stok = '$stok' 
                    WHERE id = '$id'";

    if (mysqli_query($koneksi, $query_update)) {
        header("Location: index.php?pesan=update_berhasil");
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang - Toko Kita</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="card-auth">
        <h2>✏️ Edit Barang</h2>
        <form action="" method="POST">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang']) ?>" required>
            
            <label>Harga (Rp)</label>
            <input type="number" name="harga" value="<?= $data['harga'] ?>" required>
            
            <label>Stok</label>
            <input type="number" name="stok" value="<?= $data['stok'] ?>" required>
            
            <button class="btn btn-primary" type="submit" name="update">Simpan Perubahan</button>
            <a href="../../index.php" style="display:block; text-align:center; margin-top:10px; color:#666;">Batal</a>
        </form>
    </div>
</body>
</html>