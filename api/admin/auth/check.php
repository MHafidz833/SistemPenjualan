<?php
// Pastikan tidak ada output sebelum session_start
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_secure', 'true'); // Gunakan hanya jika HTTPS
session_start();

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Debugging: Cek apakah sesi terbaca
error_log("Session ID di check.php: " . session_id());
error_log(print_r($_SESSION, true));

if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized",
        "session_id" => session_id()
    ]);
    exit;
}

// Jika sesi valid, kirim data user
echo json_encode([
    "status" => "success",
    "message" => "User is logged in",
    "session_id" => session_id(),
    "user" => [
        "admin" => $_SESSION['admin'],
        "email" => $_SESSION['email']
    ]
]);
?>
