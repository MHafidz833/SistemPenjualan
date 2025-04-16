<?php
include_once '../koneksi.php'; // Koneksi ke database

$id = $_GET['id'] ?? '';

$data = null;

if ($id) {
    $query = "SELECT a.id_pos, a.judul, a.gambar, a.tgl, m.nm_kategori, a.isi
              FROM tbl_pos a
              JOIN tbl_kat_pos m ON a.id_kategori = m.id_kategori
              WHERE a.id_pos = '$id'";

    $result = mysqli_query($db, $query);
    $data = mysqli_fetch_assoc($result);
}

echo json_encode([
    'status' => $data ? 'success' : 'error',
    'message' => $data ? 'Data berhasil ditemukan' : 'Terjadi kesalahan',
    'data' => $data ?: null
]);
//dipakai
mysqli_close($db);
