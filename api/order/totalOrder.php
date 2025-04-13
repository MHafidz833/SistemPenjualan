<?php
// Include koneksi database
require "../koneksi.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Izinkan metode GET dan POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu

// Array untuk menyimpan response
$response = array();

// Cek apakah parameter 'id' ada pada query string
if (!isset($_GET['id'])) {
    $response['status'] = 'error';
    $response['message'] = 'ID order tidak ditemukan';
    echo json_encode($response);
    exit();
}

// Ambil ID order dari query string
$id_order = $_GET['id'];

// Query untuk mengambil total pembayaran berdasarkan order ID
$query = "SELECT total_order FROM tbl_order WHERE id_order = '$id_order'";
$result = mysqli_query($db, $query);

// Cek apakah data ditemukan
if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    $response['status'] = 'success';
    $response['message'] = 'Data pembayaran berhasil ditemukan';
    $response['total_order'] = 'Rp. ' . number_format($data['total_order']);
} else {
    $response['status'] = 'error';
    $response['message'] = 'Order tidak ditemukan';
}

// Mengembalikan response dalam format JSON
echo json_encode($response);
?>