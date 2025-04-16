<?php
include('../../koneksi.php');

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

session_start();

function executeQuery($query, $db, $params = [], $types = "")
{
    $stmt = mysqli_prepare($db, $query);
    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    return $stmt;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = executeQuery("SELECT * FROM tbl_kat_pos", $db);
    $data = mysqli_fetch_all(mysqli_stmt_get_result($result), MYSQLI_ASSOC);
    echo json_encode(['status' => $data ? 'success' : 'error', 'data' => $data ?: [], 'message' => $data ? '' : 'No categories found']);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['nama'] ?? null;
    echo json_encode([
        'status' => $name && executeQuery("INSERT INTO tbl_kat_pos (nm_kategori) VALUES (?)", $db, [$name], "s") ? 'success' : 'error',
        'message' => $name ? 'Category added successfully' : 'Category name is required'
    ]);
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $id = $_GET['id'] ?? null;
    echo json_encode([
        'status' => $id && executeQuery("DELETE FROM tbl_kat_pos WHERE id_kategori=?", $db, [$id], "i") ? 'success' : 'error',
        'message' => $id ? 'Category deleted successfully' : 'Category ID is required'
    ]);
}
//dipakai
?>
