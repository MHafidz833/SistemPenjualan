<?php
// Include your database connection
require '../koneksi.php'; // assuming your database connection is here

// Set the response content type to JSON
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Izinkan metode GET dan POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu
// Get the id_produk from the URL (GET method)
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    // Query to fetch the product details
    $query = "SELECT * FROM tbl_produk WHERE id_produk='$id_produk'";
    $result = mysqli_query($db, $query);

    // Check if product exists
    if (mysqli_num_rows($result) > 0) {
        $produk = mysqli_fetch_assoc($result);

        // Response in JSON format
        echo json_encode([
            'status' => 'success',
            'data' => [
                'id_produk' => $produk['id_produk'],
                'nm_produk' => $produk['nm_produk'],
                'gambar' => $produk['gambar'],
                'harga' => $produk['harga'],
                'stok' => $produk['stok'],
                'deskripsi' => $produk['deskripsi']
            ]
        ]);
    } else {
        // Product not found
        echo json_encode([
            'status' => 'error',
            'message' => 'Produk tidak ditemukan.'
        ]);
    }
} else {
    // Missing id_produk
    echo json_encode([
        'status' => 'error',
        'message' => 'ID Produk tidak diberikan.'
    ]);
}

?>