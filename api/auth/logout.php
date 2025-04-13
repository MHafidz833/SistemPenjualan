<?php
session_start();
header('Content-Type: application/json');
// Hapus semua session yang terkait dengan pengguna
session_unset();

// Hancurkan sesi pengguna
session_destroy();

// Menyiapkan response array
$response = array();

// Kirimkan pesan logout sukses
$response['status'] = 'success';
$response['message'] = 'Anda Telah Berhasil Logout';

// Kirimkan respon dalam format JSON
echo json_encode($response);
?>