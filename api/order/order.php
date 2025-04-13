<?php

require '../koneksi.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Ambil data JSON dari request
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

// Pastikan semua field penting tersedia
$requiredFields = ['id_pelanggan', 'no_telp', 'province_destination', 'city_destination', 'kodePos', 'alamat', 'ongkir', 'subtotal', 'items'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || $data[$field] === '') {
        echo json_encode(['status' => 'error', 'message' => "Missing required field: $field"]);
        exit;
    }
}

$id_pelanggan = mysqli_real_escape_string($db, $data['id_pelanggan']);
$no_telp = mysqli_real_escape_string($db, $data['no_telp']);
$provinsi = mysqli_real_escape_string($db, $data['province_destination']);
$kota = mysqli_real_escape_string($db, $data['city_destination']);
$kodePos = mysqli_real_escape_string($db, $data['kodePos']);
$alamat = mysqli_real_escape_string($db, $data['alamat']);
$ongkir = (float) $data['ongkir'];
$subtotal = (float) $data['subtotal'];
$catatan = mysqli_real_escape_string($db, $data['catatan'] ?? '');
$items = $data['items'];
$tgl_order = date('Y-m-d');

// Hitung total harga (subtotal + ongkir)
$total_order = $subtotal + $ongkir;

// Ambil nama pelanggan
$query = "SELECT nm_pelanggan FROM tbl_pelanggan WHERE id_pelanggan = '$id_pelanggan'";
$result = mysqli_query($db, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Customer not found']);
    exit;
}
$nm_pelanggan = mysqli_fetch_assoc($result)['nm_pelanggan'];

// Insert data order
$query = "INSERT INTO tbl_order (id_pelanggan, nm_penerima, telp, provinsi, kota, kode_pos, alamat_pengiriman, catatan, tgl_order, ongkir, total_order) 
          VALUES ('$id_pelanggan', '$nm_pelanggan', '$no_telp', '$provinsi', '$kota', '$kodePos', '$alamat', '$catatan', '$tgl_order', '$ongkir', '$total_order')";
if (!mysqli_query($db, $query)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to insert order']);
    exit;
}
$id_order = mysqli_insert_id($db);

// Insert data order detail
foreach ($items as $item) {
    $id_produk = mysqli_real_escape_string($db, $item['id_produk']);
    $jumlah = (int) $item['jumlah'];

    // Cek produk
    $query = "SELECT nm_produk, harga, berat FROM tbl_produk WHERE id_produk = '$id_produk'";
    $result = mysqli_query($db, $query);
    if (!$result || mysqli_num_rows($result) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        exit;
    }

    $product = mysqli_fetch_assoc($result);
    $nm_produk = $product['nm_produk'];
    $harga = (float) $product['harga'];
    $berat = (float) $product['berat'];
    $subharga = $harga * $jumlah;
    $subberat = $berat * $jumlah;

    // Insert ke tbl_detail_order
    $query = "INSERT INTO tbl_detail_order (id_order, id_produk, nm_produk, harga, jml_order, berat, subberat, subharga) 
              VALUES ('$id_order', '$id_produk', '$nm_produk', '$harga', '$jumlah', '$berat', '$subberat', '$subharga')";
    if (!mysqli_query($db, $query)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert order details']);
        exit;
    }

    // Update stok produk
    $query = "UPDATE tbl_produk SET stok = stok - $jumlah WHERE id_produk = '$id_produk'";
    mysqli_query($db, $query);
}

// Sukses
echo json_encode([
    'status' => 'success',
    'message' => 'Order placed successfully',
    'order_id' => $id_order,
    'total_order' => $total_order
]);

?>
