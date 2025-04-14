<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include "../koneksi.php";

// Node 1: Ambil data input
$nama = $_POST['nama'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$response = [];

// Node 2: Cek validasi input kosong
if (!$nama || !$username || !$email || !$password) {
    $response['status'] = 'error';
    $response['message'] = 'Semua field harus diisi.';
    echo json_encode($response);
    exit; // Berhenti di Node 2
}

// Node 3: Periksa email atau username di database
$cek = $db->prepare("SELECT * FROM tbl_pelanggan WHERE email = ? OR username = ?");
$cek->bind_param("ss", $email, $username);
$cek->execute();
$result = $cek->get_result();

if ($result->num_rows > 0) { // Node 4
    $response['status'] = 'error';
    $response['message'] = 'Email atau Username sudah terdaftar!';
    echo json_encode($response);
    exit; // Berhenti di Node 4
}

// Node 5: Insert data baru ke database
$stmt = $db->prepare("INSERT INTO tbl_pelanggan (nm_pelanggan, username, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nama, $username, $email, $password);

if ($stmt->execute()) { // Node 6
    $response['status'] = 'success';
    $response['message'] = 'Pendaftaran berhasil!';
} else { // Node 7
    $response['status'] = 'error';
    $response['message'] = 'Terjadi kesalahan saat menyimpan data.';
}

// Node 8: Kirim respon akhir
echo json_encode($response);
?>
