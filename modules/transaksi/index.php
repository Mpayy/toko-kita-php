<?php
require '../../config/auth_check.php';
require '../../config/koneksi.php';

// 1. Query JOIN yang sudah kita bahas
$query = "SELECT 
            p.tanggal, 
            p.id AS invoice, 
            u.nama_lengkap AS kasir, 
            pr.nama_barang, 
            dp.jumlah, 
            dp.harga_satuan
          FROM detail_penjualan dp
          INNER JOIN penjualan p ON dp.penjualan_id = p.id
          INNER JOIN produk pr ON dp.produk_id = pr.id
          INNER JOIN users u ON p.kasir_id = u.id
          ORDER BY p.tanggal DESC";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi - Toko Kita</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<div class="container">
    <h2>📜 Riwayat Transaksi Penjualan</h2>
    <a href="../../index.php" style="display:block; text-align:center; margin-top:10px; color:#666;">Kembali ke Dashboard</a>
    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>No. Invoice</th>
                <th>Kasir</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_semua = 0; // Keranjang untuk menampung total
            while ($row = mysqli_fetch_assoc($result)) : 
                $subtotal = $row['jumlah'] * $row['harga_satuan'];
                $total_semua += $subtotal; // Akumulasi total
            ?>
            <tr>
                <td><?= $row['tanggal']; ?></td>
                <td>#INV-<?= $row['invoice']; ?></td>
                <td><?= $row['kasir']; ?></td>
                <td><?= $row['nama_barang']; ?></td>
                <td><?= $row['jumlah']; ?></td>
                <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.'); ?></td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="6" style="text-align: right;">Total Penjualan Keseluruhan:</td>
                <td>Rp <?= number_format($total_semua, 0, ',', '.'); ?></td>
            </tr>
        </tfoot>
    </table>
</div>