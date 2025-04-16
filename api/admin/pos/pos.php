<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include('../../koneksi.php');

function executeQuery($db, $query) {
    return mysqli_query($db, $query);
}

function fetchPosts($db) {
    $query = "SELECT p.id_pos, p.judul, k.nm_kategori, p.tgl FROM tbl_pos p JOIN tbl_kat_pos k ON p.id_kategori=k.id_kategori";
    $result = executeQuery($db, $query);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function deleteItem($db, $table, $column, $id) {
    return executeQuery($db, "DELETE FROM $table WHERE $column='$id'");
}

$response = ['status' => 'error', 'message' => 'Invalid request'];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $posts = fetchPosts($db);
        $response = $posts ? ['status' => 'success', 'data' => $posts] : ['status' => 'error', 'message' => 'No posts found'];
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $response = deleteItem($db, 'tbl_pos', 'id_pos', $_GET['id'])
                ? ['status' => 'success', 'message' => 'Post deleted successfully']
                : ['status' => 'error', 'message' => 'Failed to delete post'];
        }
        break;
}
//dipakai
echo json_encode($response);
