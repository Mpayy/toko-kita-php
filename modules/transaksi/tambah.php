<?php
require '../../config/auth_check.php';
require '../../config/koneksi.php';

// Ambil daftar produk untuk dropdown
$produk_query = mysqli_query($koneksi, "SELECT * FROM produk WHERE stok > 0");

if (isset($_POST['bayar'])) {
    $produk_id = $_POST['produk_id'];
    $jumlah    = $_POST['jumlah'];
    
    // 1. Ambil data produk (untuk harga dan cek stok)
    // $p = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = '$produk_id'");
    // $data_p = mysqli_fetch_assoc($p);

    $p = mysqli_prepare($koneksi, "SELECT * FROM produk WHERE id = ?" );
    mysqli_stmt_bind_param($p, "i", $produk_id);
    mysqli_stmt_execute($p);

    $result = mysqli_stmt_get_result($p);
    $data_p = mysqli_fetch_assoc($result);

    mysqli_stmt_close($p);
    
    if ($data_p['stok'] < $jumlah) {
        echo "<script>alert('Stok tidak mencukupi!');</script>";
    } else {
        $total = $data_p['harga'] * $jumlah;
        $kasir = $_SESSION['admin_id'];

        // START TRANSACTION (Logika Senior)
        mysqli_begin_transaction($koneksi);

        try {
            // A. Simpan ke tabel penjualan
            mysqli_query($koneksi, "INSERT INTO penjualan (total_harga, kasir_id) VALUES ('$total', '$kasir')");
            $penjualan_id = mysqli_insert_id($koneksi); // Ambil ID transaksi yang baru saja dibuat

            // B. Simpan ke detail_penjualan
            $harga_saat_ini = $data_p['harga'];
            mysqli_query($koneksi, "INSERT INTO detail_penjualan (penjualan_id, produk_id, jumlah, harga_satuan) 
                                    VALUES ('$penjualan_id', '$produk_id', '$jumlah', '$harga_saat_ini')");

            // C. POTONG STOK (Inilah kuncinya!)
            mysqli_query($koneksi, "UPDATE produk SET stok = stok - $jumlah WHERE id = '$produk_id'");

            // Jika semua ok, simpan permanen
            mysqli_commit($koneksi);
            header("Location: ../../index.php?pesan=sukses");
        } catch (Exception $e) {
            // Jika ada satu saja yang gagal, batalkan semua!
            mysqli_rollback($koneksi);
            echo "Transaksi Gagal: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi - Toko Kita</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="card-auth">
    <form method="POST">
    <label>Pilih Barang:</label>
    <select name="produk_id" required>
        <?php while($row = mysqli_fetch_assoc($produk_query)): ?>
            <option value="<?= $row['id'] ?>"><?= $row['nama_barang'] ?> (Stok: <?= $row['stok'] ?>)</option>
        <?php endwhile; ?>
    </select>
    
    <label>Jumlah Beli:</label>
    <input type="number" name="jumlah" min="1" required>
    
    <button class="btn btn-success" type="submit" name="bayar">Proses Transaksi</button>
    <a href="../../index.php" style="display:block; text-align:center; margin-top:10px; color:#666;">Batal</a>
</form>
</div>
</body>
</html>

