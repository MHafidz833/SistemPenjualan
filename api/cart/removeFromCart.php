<?php
header('Content-Type: application/json');
include_once '../koneksi.php'; // Koneksi ke database

// Memulai session untuk akses keranjang
session_start();

// Ambil ID produk yang akan dihapus dari parameter URL
if (isset($_POST['id'])) {
    $id_produk = $_POST['id'];

    // Cek apakah produk ada di dalam keranjang
    if (isset($_SESSION["cart"][$id_produk])) {
        unset($_SESSION["cart"][$id_produk]);  // Menghapus produk dari keranjang

        echo json_encode([
            'status' => 'success',
            'message' => 'Produk berhasil dihapus dari keranjang'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Produk tidak ditemukan di dalam keranjang'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID produk tidak diberikan'
    ]);
}

mysqli_close($db);
?>