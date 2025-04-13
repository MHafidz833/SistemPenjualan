<?php
header('Content-Type: application/json');
include_once '../koneksi.php'; // Koneksi ke database

// Cek apakah ada data keranjang dalam session
session_start();

// Jika session cart kosong, berikan response keranjang kosong
if (empty($_SESSION["cart"])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Keranjang kosong, silakan pilih produk terlebih dahulu'
    ]);
    exit;
}

// Jika ada, ambil detail produk di dalam keranjang
$cart_items = [];
$subtotal = 0;
foreach ($_SESSION["cart"] as $id_produk => $jumlah) {
    $query = "SELECT * FROM tbl_produk WHERE id_produk='$id_produk'";
    $result = mysqli_query($db, $query);
    $produk = mysqli_fetch_array($result);

    $subharga = $produk['harga'] * $jumlah;
    $cart_items[] = [
        'id_produk' => $produk['id_produk'],
        'nm_produk' => $produk['nm_produk'],
        'gambar' => $produk['gambar'],
        'harga' => $produk['harga'],
        'jumlah' => $jumlah,
        'subharga' => $subharga
    ];
    $subtotal += $subharga;
}

// Kirimkan response dalam format JSON
echo json_encode([
    'status' => 'success',
    'data' => $cart_items,
    'subtotal' => $subtotal
]);

mysqli_close($db);
?>