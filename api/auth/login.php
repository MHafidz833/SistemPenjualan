<?php
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Izinkan metode GET dan POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu
header('Content-Type: application/json');
include "../koneksi.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['u'];
    $password = $_POST['p'];
    $ambil = $db->query("SELECT * FROM tbl_pelanggan WHERE username = '$username' AND password = '$password'");
    $yangcocok = $ambil->num_rows;

    $response = array();

    if ($yangcocok == 1) {
        $akun = $ambil->fetch_assoc();
        $_SESSION['pelanggan'] = $akun;
        $_SESSION['email'] = $akun['email'];

        $response['status'] = 'success';
        $response['message'] = 'Anda Berhasil Login';
        $response['email'] = $akun['email'];
        $response['pelanggan'] = $akun;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Username Dan Password Anda Salah';
    }

    echo json_encode($response);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_SESSION['pelanggan'])) {
        echo json_encode([
            "status" => "success",
            "message" => "User is logged in",
            "user" => [
                "pelanggan" => $_SESSION['pelanggan'],
                "email" => $_SESSION['email']
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "User is not logged in"
        ]);
    }
}
?>
