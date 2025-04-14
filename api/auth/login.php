<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include "../koneksi.php";
session_start();

$username = $_POST['u'] ?? '';
$password = $_POST['p'] ?? '';

$result = $db->query("SELECT * FROM tbl_pelanggan WHERE username = '$username' AND password = '$password'");

if ($result->num_rows === 1) {
    $response = [
        'status' => 'success',
        'message' => 'Anda Berhasil Login',
        'pelanggan' => $result->fetch_assoc()
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Username Dan Password Anda Salah'
    ];
}

echo json_encode($response);
// dipakai
?>
