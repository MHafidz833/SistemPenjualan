<?php
header('Content-Type: application/json');
include('../../koneksi.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Direktori penyimpanan gambar
$uploadDir = '../../../fe/src/assets/admin/assets/images/foto_pos/';

// Fungsi untuk menyimpan gambar dari Base64
function saveBase64Image($base64Image, $uploadDir)
{
    // Pastikan direktori ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Cek apakah data base64 valid
    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
        $data = substr($base64Image, strpos($base64Image, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, jpeg

        // Pastikan hanya format yang diperbolehkan
        if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
            return ['status' => 'error', 'message' => 'Format gambar tidak valid. Gunakan JPG atau PNG.'];
        }

        $data = base64_decode($data);
        if ($data === false) {
            return ['status' => 'error', 'message' => 'Base64 decoding gagal.'];
        }

        // Nama file unik
        $fileName = uniqid('post_') . '.' . $type;
        $filePath = $uploadDir . $fileName;

        // Simpan gambar ke folder
        if (file_put_contents($filePath, $data)) {
            return ['status' => 'success', 'file_name' => $fileName];
        } else {
            return ['status' => 'error', 'message' => 'Gagal menyimpan gambar.'];
        }
    }

    return ['status' => 'error', 'message' => 'Format Base64 tidak valid.'];
}

// Fungsi untuk menambahkan postingan
function addPost($judul, $isi, $kategori, $gambarFileName, $db)
{
    $tgl = date('Y-m-d');

    // Cek apakah kategori valid
    $checkCategory = "SELECT id_kategori FROM tbl_kat_pos WHERE id_kategori = ?";
    $stmt = mysqli_prepare($db, $checkCategory);
    mysqli_stmt_bind_param($stmt, "i", $kategori);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Kategori tidak ditemukan.']);
        exit();
    }
    mysqli_stmt_close($stmt);

    // Simpan data ke database
    $query = "INSERT INTO tbl_pos (judul, isi, gambar, id_kategori, tgl) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "sssis", $judul, $isi, $gambarFileName, $kategori, $tgl);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}

// Handle request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['judul'], $data['isi'], $data['kategori'], $data['gambar'])) {
        echo json_encode(['status' => 'error', 'message' => 'Semua data harus diisi.']);
        exit();
    }

    $judul = $data['judul'];
    $isi = $data['isi'];
    $kategori = (int) $data['kategori'];
    $base64Gambar = $data['gambar']; // Gambar dalam format Base64

    if (empty($judul) || empty($isi) || empty($kategori) || empty($base64Gambar)) {
        echo json_encode(['status' => 'error', 'message' => 'Judul, isi, kategori, dan gambar tidak boleh kosong.']);
        exit();
    }

    // Simpan gambar
    $saveImage = saveBase64Image($base64Gambar, $uploadDir);
    if ($saveImage['status'] === 'error') {
        echo json_encode($saveImage);
        exit();
    }

    $gambarFileName = $saveImage['file_name'];

    // Tambahkan postingan ke database
    if (addPost($judul, $isi, $kategori, $gambarFileName, $db)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Postingan berhasil ditambahkan.',
            'data' => [
                'judul' => $judul,
                'isi' => $isi,
                'kategori' => $kategori,
                'gambar' => $gambarFileName
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data postingan.']);
    }
}
?>