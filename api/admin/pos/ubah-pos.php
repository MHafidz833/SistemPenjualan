<?php
header('Content-Type: application/json');
include('../../koneksi.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Direktori penyimpanan gambar
$uploadDir = '../../../fe/src/assets/admin/assets/images/foto_pos/';

// Fungsi menyimpan gambar dari Base64
function saveBase64Image($base64Image, $uploadDir)
{
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
        $data = substr($base64Image, strpos($base64Image, ',') + 1);
        $type = strtolower($type[1]);

        if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
            return ['status' => 'error', 'message' => 'Format gambar tidak valid. Gunakan JPG atau PNG.'];
        }

        $data = base64_decode($data);
        if ($data === false) {
            return ['status' => 'error', 'message' => 'Base64 decoding gagal.'];
        }

        $fileName = uniqid('post_') . '.' . $type;
        $filePath = $uploadDir . $fileName;

        if (file_put_contents($filePath, $data)) {
            return ['status' => 'success', 'file_name' => $fileName];
        } else {
            return ['status' => 'error', 'message' => 'Gagal menyimpan gambar.'];
        }
    }

    return ['status' => 'error', 'message' => 'Format Base64 tidak valid.'];
}

// Fungsi update postingan
function updatePost($id, $judul, $isi, $kategori, $gambarFileName, $db)
{
    $tgl = date('Y-m-d');

    // Cek apakah postingan ada
    $checkPost = "SELECT gambar FROM tbl_pos WHERE id_pos = ?";
    $stmt = mysqli_prepare($db, $checkPost);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $gambarLama);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$gambarLama) {
        echo json_encode(['status' => 'error', 'message' => 'Postingan tidak ditemukan.']);
        exit();
    }

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

    // Hapus gambar lama jika ada gambar baru
    global $uploadDir;
    if ($gambarFileName && $gambarLama) {
        $oldFilePath = $uploadDir . $gambarLama;
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }

    // Update postingan di database
    $query = "UPDATE tbl_pos SET judul = ?, isi = ?, gambar = ?, id_kategori = ?, tgl = ? WHERE id_pos = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "sssisi", $judul, $isi, $gambarFileName, $kategori, $tgl, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}

// Handle request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['id'], $data['judul'], $data['isi'], $data['kategori'])) {
        echo json_encode(['status' => 'error', 'message' => 'Semua data harus diisi.']);
        exit();
    }

    $id = (int) $data['id'];
    $judul = $data['judul'];
    $isi = $data['isi'];
    $kategori = (int) $data['kategori'];
    $base64Gambar = $data['gambar'] ?? null;

    if (empty($id) || empty($judul) || empty($isi) || empty($kategori)) {
        echo json_encode(['status' => 'error', 'message' => 'ID, judul, isi, dan kategori tidak boleh kosong.']);
        exit();
    }

    // Simpan gambar jika ada
    $gambarFileName = null;
    if ($base64Gambar) {
        $saveImage = saveBase64Image($base64Gambar, $uploadDir);
        if ($saveImage['status'] === 'error') {
            echo json_encode($saveImage);
            exit();
        }
        $gambarFileName = $saveImage['file_name'];
    }

    // Update postingan di database
    if (updatePost($id, $judul, $isi, $kategori, $gambarFileName, $db)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Postingan berhasil diperbarui.',
            'data' => [
                'id' => $id,
                'judul' => $judul,
                'isi' => $isi,
                'kategori' => $kategori,
                'gambar' => $gambarFileName
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data postingan.']);
    }
}
?>