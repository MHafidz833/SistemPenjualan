<?php
// Pastikan tidak ada output sebelum session_start
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', 'true'); 
session_start();

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Periksa login admin
if (!isset($_SESSION['admin'])) {
    die(json_encode(["status" => "error", "message" => "Unauthorized", "session_id" => session_id()]));
}

include('../../koneksi.php');

// Fungsi utama: Ambil atau hapus pelanggan
function manageCustomers($db, $method) {
    if ($method === 'GET') {
        $result = mysqli_query($db, "SELECT * FROM tbl_pelanggan");
        return json_encode(["status" => $result->num_rows ? "success" : "error", "data" => mysqli_fetch_all($result, MYSQLI_ASSOC)]);
    }

    if ($method === 'POST' && ($id = $_POST['id'] ?? null)) {
        $stmt = $db->prepare("DELETE FROM tbl_pelanggan WHERE id_pelanggan = ?");
        $stmt->bind_param("s", $id);
        return json_encode(["status" => $stmt->execute() ? "success" : "error", "message" => $stmt->execute() ? "Pelanggan berhasil dihapus" : "Gagal menghapus pelanggan"]);
    }

    return json_encode(["status" => "error", "message" => "Permintaan tidak valid"]);
}

echo manageCustomers($db, $_SERVER['REQUEST_METHOD']);
//dipake
?>
