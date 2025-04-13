<?php
include('../../koneksi.php');


// Menetapkan header JSON
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS"); // Izinkan metode GET, POST, DELETE, PUT
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu

// Memulai sesi dan menghubungkan ke database
session_start();
include('../../koneksi.php');

// Fungsi untuk mendapatkan daftar kategori
function getAllCategories($db)
{
    $query = "SELECT * FROM tbl_kat_pos";
    $result = mysqli_query($db, $query);
    $categories = [];

    while ($data = mysqli_fetch_assoc($result)) {
        $categories[] = $data;
    }

    return $categories;
}

// Fungsi untuk menambahkan kategori
function addCategory($name, $db)
{
    $query = "INSERT INTO tbl_kat_pos (nm_kategori) VALUES ('$name')";
    return mysqli_query($db, $query);
}

// Fungsi untuk menghapus kategori berdasarkan id_kategori
function deleteCategory($id, $db)
{
    $query = "DELETE FROM tbl_kat_pos WHERE id_kategori='$id'";
    return mysqli_query($db, $query);
}

// Mengambil daftar kategori (GET request)
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $categories = getAllCategories($db);

    echo json_encode([
        'status' => count($categories) > 0 ? 'success' : 'error',
        'data' => $categories,
        'message' => count($categories) > 0 ? '' : 'No categories found'
    ]);
}

// Menambahkan kategori baru (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['nama'])) {
        $name = $data['nama'];
        $result = addCategory($name, $db);

        echo json_encode([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Category added successfully' : 'Failed to add category'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Category name is required'
        ]);
    }
}

// Menghapus kategori (DELETE request)
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id) {
        $result = deleteCategory($id, $db);

        echo json_encode([
            'status' => $result ? 'success' : 'error',
            'message' => $result ? 'Category deleted successfully' : 'Failed to delete category'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Category ID is required'
        ]);
    }
}