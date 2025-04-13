<?php
// Konfigurasi session
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', 'true'); // Aktifkan hanya untuk HTTPS
session_start();

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include "../../koneksi.php";

// Debugging: Memeriksa sesi
error_log("Session ID di login.php: " . session_id());
error_log(print_r($_SESSION, true));

// Cek jenis request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses login
    $username = $_POST['u'] ?? '';
    $password = $_POST['p'] ?? '';

    $response = [];

    $ambil = $db->prepare("SELECT * FROM tbl_admin WHERE username = ? AND password = ?");
    $ambil->bind_param("ss", $username, $password);
    $ambil->execute();
    $result = $ambil->get_result();

    if ($result->num_rows === 1) {
        $akun = $result->fetch_assoc();
        $_SESSION['admin'] = $akun['username'];
        $_SESSION['email'] = $akun['email'];

        $response = [
            'status' => 'success',
            'message' => 'Login berhasil!',
            'session_id' => session_id(),
            'admin' => $akun['username'],
            'email' => $akun['email']
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Username atau password salah!'
        ];
    }
    echo json_encode($response);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validasi status login
    if (!isset($_SESSION['admin'])) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Unauthorized",
            "session_id" => session_id()
        ]);
        exit;
    }

    // Jika sesi valid, kirim data pengguna
    echo json_encode([
        "status" => "success",
        "message" => "User is logged in",
        "session_id" => session_id(),
        "user" => [
            "admin" => $_SESSION['admin'],
            "email" => $_SESSION['email']
        ]
    ]);
}
?>
