<?php
header('Content-Type: application/json');
include('../../koneksi.php');

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, DELETE,PUT, OPTIONS"); // Izinkan metode GET dan POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu

// Fungsi untuk mengambil data postingan dan kategori
function getPosts($db)
{
    $query = "SELECT p.id_pos, p.judul, k.nm_kategori, p.tgl FROM tbl_pos p JOIN tbl_kat_pos k ON p.id_kategori=k.id_kategori";
    $result = mysqli_query($db, $query);

    $posts = [];
    while ($data = mysqli_fetch_assoc($result)) {
        $posts[] = $data;
    }
    return $posts;
}

// Fungsi untuk menghapus postingan
function deletePost($id, $db)
{
    $query = "DELETE FROM tbl_pos WHERE id_pos='$id'";
    return mysqli_query($db, $query);
}

// Fungsi untuk menghapus kategori
function deleteCategory($id, $db)
{
    $query = "DELETE FROM tbl_kat_pos WHERE id_kategori='$id'";
    return mysqli_query($db, $query);
}

// Mengambil daftar postingan (GET request)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $posts = getPosts($db);

    if (count($posts) > 0) {
        echo json_encode([
            'status' => 'success',
            'data' => $posts
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No posts found'
        ]);
    }
}

// Menghapus postingan berdasarkan id (DELETE request)
if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = deletePost($id, $db);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Post deleted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to delete post'
        ]);
    }
}