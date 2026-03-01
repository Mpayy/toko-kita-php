<?php
require '../../config/auth_check.php';
include '../../config/koneksi.php'; // Mengambil kunci pintu database

if(isset($_POST["simpan"])){
    // 1. Tangkap data dari form
    $nama = mysqli_real_escape_string($koneksi, $_POST["nama_barang"]);
    $harga = mysqli_real_escape_string($koneksi, $_POST["harga"]);
    $stok = mysqli_real_escape_string($koneksi, $_POST["stok"]);

    // 2. Siapkan perintah SQL (Query)
    // TULIS QUERY INSERT DI SINI
    $query = "INSERT INTO produk (nama_barang, harga, stok) VALUE ('$nama', '$harga', '$stok')";

    // 3. Eksekusi Query
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?pesan=tambah_berhasil");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Inventaris - Toko Kita</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="card-auth">
        <h2>📦 Tambah Barang</h2>

        <form action="tambah_barang.php" method="POST">
            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" required>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" required>
            </div>
            <div class="form-group">
                <label>Stok Awal</label>
                <input type="number" name="stok" required>
            </div>
            <button class="btn btn-primary" type="submit" name="simpan">Simpan ke Gudang</button>
            <a href="../../index.php" style="display:block; text-align:center; margin-top:10px; color:#666;">Batal</a>
        </form>
    </div>
</body>
</html>