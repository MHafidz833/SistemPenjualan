<?php
include "../koneksi.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$response = [];

// Unified function to fetch data
function fetchData($db, $query) {
    $result = mysqli_query($db, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Ambil kategori dari parameter URL
$kategori = $_GET['kategori'] ?? null;

// Fetch categories
$categories = fetchData($db, "SELECT * FROM tbl_kat_produk");

// Fetch products dengan kondisi kategori
$query = "SELECT * FROM tbl_produk";
if ($kategori) {
    $query .= " WHERE id_kategori='" . mysqli_real_escape_string($db, $kategori) . "'";
}

// Cek jika ada produk yang ditemukan
$products = fetchData($db, $query);

// Menambahkan kondisi percabangan berdasarkan jumlah produk yang ditemukan
if (count($products) > 0) {
    $response = [
        'status' => 'success',
        'message' => 'Data retrieved successfully',
        'categories' => $categories,
        'products' => array_map(function ($produk) {
            return [
                'id_produk' => $produk['id_produk'],
                'nm_produk' => $produk['nm_produk'],
                'harga' => number_format($produk['harga']),
                'gambar' => $produk['gambar'],
                'id_kategori' => $produk['id_kategori']
            ];
        }, $products)
    ];
} else {
    // Menambahkan percabangan jika produk tidak ditemukan
    $response = [
        'status' => 'error',
        'message' => 'No products found'
    ];
}

echo json_encode($response);
?>
