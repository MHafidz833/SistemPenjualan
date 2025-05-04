<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include "../koneksi.php";

$email = $_POST['email'] ?? '';
$new_password = $_POST['password'] ?? '';

if (empty($email) || empty($new_password)) {
    echo json_encode(['status' => 'error', 'message' => 'Email dan Password Baru harus diisi!']);
    exit;
}

$query = $db->prepare("SELECT * FROM tbl_pelanggan WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();

if ($query->get_result()->num_rows) {
    $update = $db->prepare("UPDATE tbl_pelanggan SET password = ? WHERE email = ?");
    $update->bind_param("ss", $new_password, $email);

    $status = $update->execute() ? 'success' : 'error';
    $message = $status === 'success' ? 'Password berhasil direset!' : 'Gagal mereset password, coba lagi nanti.';
} else {
    $status = 'error';
    $message = 'Email tidak terdaftar!';
}
echo json_encode(['status' => $status, 'message' => $message]);
?>
