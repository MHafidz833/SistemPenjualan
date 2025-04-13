<?php
session_start();
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// Hapus semua session yang terkait
session_unset();
session_destroy();

// Response logout sukses
echo json_encode([
    'status' => 'success',
    'message' => 'Anda Telah Berhasil Logout'
]);
?>
