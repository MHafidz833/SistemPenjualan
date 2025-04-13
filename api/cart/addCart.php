<?php
header('Content-Type: application/json');
include_once '../koneksi.php'; // Koneksi ke database

// Mulai session
session_start();

// Pastikan ada ID produk dan jumlah yang valid
if (!isset($_POST['id_produk']) || !isset($_POST['jumlah']) || !is_numeric($_POST['jumlah']) || $_POST['jumlah'] <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID produk atau jumlah tidak valid.'
    ]);
    exit;
}

$id_produk = $_POST['id_produk'];
$jumlah = (int) $_POST['jumlah']; // Pastikan jumlah adalah integer

// Cek apakah produk ada di database
$query = "SELECT * FROM tbl_produk WHERE id_produk = '$id_produk'";
$result = mysqli_query($db, $query);
$produk = mysqli_fetch_array($result);

if (!$produk) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Produk tidak ditemukan.'
    ]);
    exit;
}

// Jika keranjang belum ada, inisialisasi session cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Jika produk sudah ada di keranjang, tambahkan jumlahnya
if (isset($_SESSION['cart'][$id_produk])) {
    $_SESSION['cart'][$id_produk] += $jumlah;
} else {
    // Jika produk belum ada di keranjang, tambahkan produk ke keranjang
    $_SESSION['cart'][$id_produk] = $jumlah;
}

// Kirimkan response sukses
echo json_encode([
    'status' => 'success',
    'message' => 'Produk berhasil ditambahkan ke keranjang',
    'data' => [
        'id_produk' => $produk['id_produk'],
        'nm_produk' => $produk['nm_produk'],
        'harga' => $produk['harga'],
        'jumlah' => $_SESSION['cart'][$id_produk],
        'gambar' => $produk['gambar']
    ]
]);

mysqli_close($db);
?>