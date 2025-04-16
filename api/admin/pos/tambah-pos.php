<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include('../../koneksi.php');

$uploadDir = '../../../fe/src/assets/admin/assets/images/foto_pos/';

function saveBase64Image($base64Image, $uploadDir) {
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type);

    $data = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));
    $fileName = uniqid('post_') . '.' . strtolower($type[1]);

    return file_put_contents($uploadDir . $fileName, $data) 
        ? ['status' => 'success', 'file_name' => $fileName] 
        : ['status' => 'error', 'message' => 'Gagal menyimpan gambar.'];
}

function addPost($db, $judul, $isi, $kategori, $gambarFileName) {
    $stmt = mysqli_prepare($db, "INSERT INTO tbl_pos (judul, isi, gambar, id_kategori, tgl) VALUES (?, ?, ?, ?, ?)");
    $tgl = date('Y-m-d');
    mysqli_stmt_bind_param($stmt, "sssis", $judul, $isi, $gambarFileName, $kategori, $tgl);
    return mysqli_stmt_execute($stmt);
}

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $saveImage = saveBase64Image($data['gambar'], $uploadDir);
    
    $response = addPost($db, $data['judul'], $data['isi'], (int) $data['kategori'], $saveImage['file_name'])
        ? ['status' => 'success', 'message' => 'Postingan berhasil ditambahkan.', 'data' => $data]
        : ['status' => 'error', 'message' => 'Gagal menyimpan data postingan.'];

    exit(json_encode($response));
}

?>
