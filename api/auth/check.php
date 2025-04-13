<?php
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Izinkan metode GET dan POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu
header('Content-Type: application/json');
session_start();

// Cek apakah pengguna sudah login
if (isset($_SESSION['pelanggan'])) {
    // Jika sudah login, kirimkan respons sukses dengan informasi pengguna
    echo json_encode([
        "status" => "success",
        "message" => "User is logged in",
        "user" => [
            "pelanggan" => $_SESSION['pelanggan'],
            "email" => $_SESSION['email']
        ]
    ]);
} else {
    // Jika belum login, kirimkan respons gagal
    echo json_encode([
        "status" => "error",
        "message" => "User is not logged in"
    ]);
}
?>