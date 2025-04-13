<?php
// Include header untuk koneksi database
require "../koneksi.php";

// Array untuk menyimpan response
$response = array();

header('Content-Type: application/json');
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Izinkan metode GET dan POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu
// Cek apakah parameter id tersedia
if (!isset($_GET['id'])) {
    $response['status'] = 'error';
    $response['message'] = 'ID order tidak ditemukan';
    echo json_encode($response);
    exit();
}

// Ambil ID order dari query string
$id_order = $_GET['id'];

// Query untuk mengambil rincian produk berdasarkan order ID
$sql = "SELECT * FROM tbl_detail_order d JOIN tbl_produk p ON d.id_produk = p.id_produk WHERE id_order = '$id_order'";
$query = mysqli_query($db, $sql);

// Cek apakah ada data produk terkait dengan order
if (mysqli_num_rows($query) > 0) {
    $produk_list = array();
    $no = 1;

    while ($produk = mysqli_fetch_assoc($query)) {
        $produk_item = array(
            'no' => $no,
            'gambar' => $produk['gambar'] ? 'admin/assets/images/foto_produk/' . $produk['gambar'] : null,
            'nama_produk' => $produk['nm_produk'],
            'jumlah' => $produk['jml_order']
        );

        $produk_list[] = $produk_item;
        $no++;
    }

    // Menambahkan data produk ke response
    $response['status'] = 'success';
    $response['message'] = 'Data rincian produk berhasil ditemukan';
    $response['data'] = $produk_list;

    echo json_encode($response);
} else {
    // Jika tidak ada produk untuk order tersebut
    $response['status'] = 'error';
    $response['message'] = 'Tidak ada produk ditemukan untuk order ini';
    echo json_encode($response);
}

?>