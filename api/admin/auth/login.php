<?php

session_start();
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');
include "../../koneksi.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $username = $_POST['u'] ?? '';
    $password = $_POST['p'] ?? '';

    $stmt = $db->prepare("SELECT * FROM tbl_admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['admin'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        echo json_encode([
            'status' => 'success',
            'message' => 'Login berhasil!',
            'session_id' => session_id(),
            'admin' => $user['username'],
            'email' => $user['email']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Username atau password salah!'
        ]);
    }
} elseif ($method === 'GET') {
    if (!isset($_SESSION['admin'])) {
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'Unauthorized',
            'session_id' => session_id()
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'message' => 'User is logged in',
            'session_id' => session_id(),
            'user' => [
                'admin' => $_SESSION['admin'],
                'email' => $_SESSION['email']
            ]
        ]);
    }
}
?>
