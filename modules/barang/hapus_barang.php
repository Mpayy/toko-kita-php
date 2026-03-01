<?php
// 1. Koneksi adalah Jembatan
require '../../config/auth_check.php';
include '../../config/koneksi.php';

// 2. Menangkap Identitas (ID)
// Mengambil ID dari URL (misal: hapus_barang.php?id=5)
$id = $_GET["id"];

// 3. Keamanan Dasar: Validasi
if(!is_numeric($id)){
    die("ID tidak valid");
}

// 4. Perintah Eksekusi (Query)
// Logika: Hapus HANYA baris yang ID-nya sama dengan yang dipilih
// Note: Jangan sampai lupa WHERE
$query = "DELETE FROM produk WHERE id = '$id'";

// 5. Eksekusi dan Feedback
if(mysqli_query($koneksi, $query)){
    // 6. Redirect (Pengalihan)
    // Setelah hapus, jangan biarkan user di halaman kosong. 
    // Kita lempar kembali ke index agar mereka lihat datanya sudah hilang.
    header("location: index.php?pesan=hapus_berhasil");
} else {
    echo "Gagal menghapus: " . mysqli_error($koneksi);
}

?>