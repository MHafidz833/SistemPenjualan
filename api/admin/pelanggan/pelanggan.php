<?php
// Pastikan tidak ada output sebelum session_start
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', 'true'); // Gunakan hanya jika HTTPS
session_start();

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized",
        "session_id" => session_id()
    ]);
    exit;
}

include('../../koneksi.php');

// Fungsi untuk mendapatkan daftar pelanggan
function getAllCustomers($db)
{
    $query = "SELECT * FROM tbl_pelanggan";
    $result = mysqli_query($db, $query);
    $customers = [];

    while ($data = mysqli_fetch_assoc($result)) {
        $customers[] = $data;
    }

    return $customers;
}

// Fungsi untuk menghapus pelanggan berdasarkan id_pelanggan
function deleteCustomer($id, $db)
{
    $stmt = $db->prepare("DELETE FROM tbl_pelanggan WHERE id_pelanggan = ?");
    $stmt->bind_param("s", $id);
    return $stmt->execute();
}

// GET: Ambil data pelanggan
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $customers = getAllCustomers($db);
    echo json_encode([
        'status' => count($customers) > 0 ? 'success' : 'error',
        'data' => $customers,
        'message' => count($customers) > 0 ? '' : 'No customers found',
        'session_id' => session_id() // Debugging
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        $stmt = $db->prepare("DELETE FROM tbl_pelanggan WHERE id_pelanggan = ?");
        $stmt->bind_param("s", $id);
        $deleteStatus = $stmt->execute();

        if ($deleteStatus) {
            echo json_encode(["status" => "success", "message" => "Pelanggan berhasil dihapus"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menghapus pelanggan"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ID pelanggan diperlukan"]);
    }
}



?>
