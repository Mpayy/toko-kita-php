<?php
require 'config/auth_check.php';
include 'config/koneksi.php';

// 1. Tulis query untuk mengambil semua data dari tabel produk
$query = "SELECT * FROM produk ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Inventaris - Toko Kita</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <p>Halo, <strong><?= $_SESSION['nama']; ?></strong>! | <a href="auth/logout.php">Keluar dari Sistem</a></p>
        <h2>📦 Daftar Stok Barang</h2>
        <a href="modules/barang/tambah_barang.php" class="btn-tambah">+ Tambah Barang Baru</a>
        <a href="modules/transaksi/tambah.php" class="btn-tambah">+ Tambah Transaksi Baru</a>
        <a href="modules/transaksi/index.php" class="btn-tambah">Lihat Riwayat Transaksi</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Perubahan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // 2. Gunakan perulangan (while) untuk menampilkan setiap baris data
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    // Terapkan htmlspecialchars di sini!
                    echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
                    echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                    echo "<td>" . $row['stok'] . "</td>";
                    echo "<td>
                            <button class='btn btn-success'><a href='modules/barang/edit_barang.php?id=" . $row['id'] . "'>Edit</a></button> | 
                            <button class='btn btn-danger'><a href='modules/barang/hapus_barang.php?id=" . $row['id'] . "' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a></button>
                        </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>