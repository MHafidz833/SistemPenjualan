<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Izinkan metode GET dan POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu
include_once '../koneksi.php'; // Koneksi ke database

// Ambil parameter ID artikel dari URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Pastikan ID tidak kosong
if (!empty($id)) {
    // Query untuk mengambil artikel berdasarkan ID
    $query = "SELECT a.id_pos, a.judul, a.gambar, a.tgl, m.nm_kategori, a.isi
              FROM tbl_pos a
              JOIN tbl_kat_pos m ON a.id_kategori = m.id_kategori
              WHERE a.id_pos = '$id'";

    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_array($result);
        $article = [
            'id_pos' => $data['id_pos'],
            'judul' => $data['judul'],
            'gambar' => $data['gambar'],
            'tgl' => date("F d, Y", strtotime($data['tgl'])),
            'nm_kategori' => $data['nm_kategori'],
            'isi' => $data['isi']
        ];

        // Mengirim data dalam format JSON
        echo json_encode([
            'status' => 'success',
            'data' => $article
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Artikel tidak ditemukan'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID artikel tidak ditemukan'
    ]);
}

// Menutup koneksi
mysqli_close($db);
?>