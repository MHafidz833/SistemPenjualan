<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include "../koneksi.php";

$nama = $_POST['nama'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$response = [];

$cek = $db->prepare("SELECT * FROM tbl_pelanggan WHERE email = ? OR username = ?");
$cek->bind_param("ss", $email, $username);
$cek->execute();
if ($cek->get_result()->num_rows > 0) {
    $response['status'] = 'error';
    $response['message'] = 'Email atau Username sudah terdaftar!';
} else {
    $stmt = $db->prepare("INSERT INTO tbl_pelanggan (nm_pelanggan, username, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $username, $email, $password);
    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Pendaftaran berhasil!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Terjadi kesalahan.';
    }
}

echo json_encode($response);
?>
