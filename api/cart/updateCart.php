<?php
header('Content-Type: application/json');
include_once '../koneksi.php'; // Koneksi ke database

// Memulai session untuk akses keranjang
session_start();

// Ambil ID produk dan jumlah baru dari parameter URL
if (isset($_POST['id']) && isset($_POST['jumlah'])) {
    $id_produk = $_POST['id'];
    $jumlah_baru = $_POST['jumlah'];

    // Cek apakah jumlahnya valid
    if ($jumlah_baru <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Jumlah produk tidak valid'
        ]);
        exit;
    }

    // Cek apakah produk ada di dalam keranjang
    if (isset($_SESSION["cart"][$id_produk])) {
        $_SESSION["cart"][$id_produk] = $jumlah_baru;  // Update jumlah produk dalam keranjang

        echo json_encode([
            'status' => 'success',
            'message' => 'Jumlah produk berhasil diperbarui'
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
        'message' => 'ID produk dan jumlah tidak diberikan'
    ]);
}

mysqli_close($db);
?>