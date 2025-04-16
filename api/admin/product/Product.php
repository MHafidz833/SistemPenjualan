<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handling OPTIONS request (for CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require "../../koneksi.php";

// Fungsi untuk membuat produk baru
function create_product($db, $data)
{
    error_log(print_r($data, true)); // Cek data yang diterima PHP

    if (!isset($data['id_kategori'], $data['nama'], $data['berat'], $data['harga'], $data['stok'], $data['deskripsi'], $data['img'])) {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
        return;
    }

    $kategori = $data['id_kategori'];
    $nmProduk = $data['nama'];
    $berat = $data['berat'];
    $harga = $data['harga'];
    $stok = $data['stok'];
    $deskripsi = $data['deskripsi'];
    $gambar = $data['img']['data'] ?? null;
    $namaFile = $data['img']['name'] ?? null;

    $gambar = $data['img']['data'] ?? null;
$namaFile = $data['img']['name'] ?? null;

if ($gambar && $namaFile) {
    $folder = "../../../fe/src/assets/admin/assets/images/foto_produk/";
    $filename = time() . "_" . basename($namaFile);
    $path = $folder . $filename;

    if (file_put_contents($path, base64_decode($gambar))) {
        $query = "INSERT INTO tbl_produk (id_kategori, nm_produk, berat, harga, stok, gambar, deskripsi) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "isssiss", $kategori, $nmProduk, $berat, $harga, $stok, $filename, $deskripsi);
        $exec = mysqli_stmt_execute($stmt);

        echo json_encode($exec ? ["status" => "success", "message" => "Produk berhasil ditambahkan"]
            : ["status" => "error", "message" => "Gagal menambahkan produk"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan gambar"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Data gambar tidak lengkap"]);
}

}


// Fungsi untuk memperbarui produk
function update_product($db, $data)
{
    if (!isset($data['id_produk'], $data['id_kategori'], $data['nama'], $data['berat'], $data['harga'], $data['stok'], $data['deskripsi'])) {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
        return;
    }

    $id = $data['id_produk'];
    $kategori = $data['id_kategori'];
    $nmProduk = $data['nama'];
    $berat = $data['berat'];
    $harga = $data['harga'];
    $stok = $data['stok'];
    $deskripsi = $data['deskripsi'];
    $gambar = isset($data['img']['data']) ? $data['img']['data'] : null;
    $namaFile = isset($data['img']['name']) ? $data['img']['name'] : null;

    if ($gambar) {
        // Hapus gambar lama
        $query = "SELECT gambar FROM tbl_produk WHERE id_produk=?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $produk = mysqli_fetch_assoc($result);
    
        if ($produk && !empty($produk['gambar'])) {
            $oldImagePath = "../../../fe/src/assets/admin/assets/images/foto_produk/" . $produk['gambar'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Hapus gambar lama
            }
        }
    
        // Upload gambar baru
        $folder = "../../../fe/src/assets/admin/assets/images/foto_produk/";
        $filename = time() . "_" . basename($namaFile);
        $path = $folder . $filename;
        file_put_contents($path, base64_decode($gambar));
    
        $query = "UPDATE tbl_produk SET id_kategori=?, nm_produk=?, berat=?, harga=?, stok=?, gambar=?, deskripsi=? WHERE id_produk=?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "isssissi", $kategori, $nmProduk, $berat, $harga, $stok, $filename, $deskripsi, $id);
    }
    

    $exec = mysqli_stmt_execute($stmt);
    echo json_encode($exec ? ["status" => "success", "message" => "Produk berhasil diperbarui"]
        : ["status" => "error", "message" => "Gagal memperbarui produk"]);
}

// Fungsi untuk menghapus produk
function delete_product($db, $id)
{
    $query = "SELECT gambar FROM tbl_produk WHERE id_produk=?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $produk = mysqli_fetch_assoc($result);

    if ($produk) {
        if (!empty($produk['gambar'])) {
            $imagePath = "../../../fe/src/assets/admin/assets/images/foto_produk/" . $produk['gambar'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $query = "DELETE FROM tbl_produk WHERE id_produk=?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $exec = mysqli_stmt_execute($stmt);

        echo json_encode($exec ? ["status" => "success", "message" => "Produk berhasil dihapus"]
            : ["status" => "error", "message" => "Gagal menghapus produk"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Produk tidak ditemukan"]);
    }
}

// Fungsi untuk mendapatkan semua produk
function get_all_products($db)
{
    $query = "SELECT * FROM tbl_produk";
    $result = mysqli_query($db, $query);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($products ? ["status" => "success", "products" => $products]
        : ["status" => "error", "message" => "Produk tidak ditemukan"]);
}

// Fungsi untuk mendapatkan produk berdasarkan ID
function get_product($db, $id)
{
    $query = "SELECT * FROM tbl_produk WHERE id_produk=?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);

    echo json_encode($product ? ["status" => "success", "product" => $product]
        : ["status" => "error", "message" => "Produk tidak ditemukan"]);
}

// Menangani request
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        create_product($db, $data);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        update_product($db, $data);
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id) {
            delete_product($db, $id);
        } else {
            echo json_encode(["status" => "error", "message" => "ID produk diperlukan"]);
        }
        break;

    case 'GET':
        $id = $_GET['id'] ?? null;
        $id ? get_product($db, $id) : get_all_products($db);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Metode request tidak valid"]);
        break;
        //dipakai
}
?>