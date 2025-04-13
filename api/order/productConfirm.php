<?php
// Include file koneksi ke database
require_once "../koneksi.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Izinkan metode GET dan POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu

// Menyiapkan response array
$response = array();

// Cek apakah parameter 'id' ada di URL
if (isset($_GET['id'])) {
    // Ambil id_order dari URL dan sanitasi untuk menghindari SQL Injection
    $id = mysqli_real_escape_string($db, $_GET['id']);

    // Query untuk memperbarui status pada tabel tbl_order
    $query = "UPDATE tbl_order SET status='Produk Diterima' WHERE id_order='$id'";

    // Eksekusi query
    if (mysqli_query($db, $query)) {
        // Jika query berhasil, set status dan message dalam array response
        $response['status'] = 'success';
        $response['message'] = 'Status berhasil diperbarui dan redirect ke halaman orderan.';
    } else {
        // Jika query gagal, set status dan message dalam array response
        $response['status'] = 'error';
        $response['message'] = 'Terjadi kesalahan saat memperbarui data: ' . mysqli_error($db);
    }
} else {
    // Jika id tidak ditemukan, set status dan message dalam array response
    $response['status'] = 'error';
    $response['message'] = 'ID tidak ditemukan.';
}

// Tutup koneksi database
mysqli_close($db);

// Mengirimkan response dalam format JSON
echo json_encode($response);
?>