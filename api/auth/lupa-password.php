<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include "../koneksi.php";

// Ambil data dari POST request
$email = $_POST['email'] ?? '';
$new_password = $_POST['password'] ?? '';

// Response array
$response = [];

// Validasi input sederhana
if (empty($email) || empty($new_password)) {
    $response['status'] = 'error';
    $response['message'] = 'Email dan Password Baru harus diisi!';
    echo json_encode($response);
    exit;
}

// Cek apakah email terdaftar di tabel pelanggan
$cek = $db->prepare("SELECT * FROM tbl_pelanggan WHERE email = ?");
$cek->bind_param("s", $email);
$cek->execute();
$result = $cek->get_result();

if ($result->num_rows == 1) {
    // Email ditemukan, update password baru (plain text)
    $update = $db->prepare("UPDATE tbl_pelanggan SET password = ? WHERE email = ?");
    $update->bind_param("ss", $new_password, $email);

    if ($update->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Password berhasil direset!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Gagal mereset password, coba lagi nanti.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Email tidak terdaftar!';
}

echo json_encode($response);
?>
