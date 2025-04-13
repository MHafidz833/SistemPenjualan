<?php
include "../koneksi.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$response = [];

try {
    $qkat = "SELECT * FROM tbl_kat_produk";
    $reskat = mysqli_query($db, $qkat);
    if (!$reskat) {
        throw new Exception("Error fetching categories: " . mysqli_error($db));
    }
    $categories = mysqli_fetch_all($reskat, MYSQLI_ASSOC);

    $query = "SELECT * FROM tbl_produk";
    if (!empty($_GET['kategori'])) {
        $kategori = mysqli_real_escape_string($db, $_GET['kategori']);
        $query .= " WHERE id_kategori='$kategori'";
    } elseif (!empty($_GET['select'])) {
        $search = mysqli_real_escape_string($db, $_GET['select']);
        $query .= " WHERE nm_produk LIKE '%$search%'";
    }
    $query .= " ORDER BY id_produk ASC";

    $result = mysqli_query($db, $query);
    if (!$result) {
        throw new Exception("Error fetching products: " . mysqli_error($db));
    }
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
