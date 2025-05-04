<?php

require '../koneksi.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) exit;

$requiredFields = ['id_pelanggan', 'no_telp', 'province_destination', 'city_destination', 'kodePos', 'alamat', 'ongkir', 'subtotal', 'items'];
foreach ($requiredFields as $field) {
    if (empty($data[$field])) exit;
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
$tgl_order = date('Y-m-d');
$total_order = $subtotal + $ongkir;

// Ambil nama pelanggan
$query = "SELECT nm_pelanggan FROM tbl_pelanggan WHERE id_pelanggan = '$id_pelanggan'";
$result = mysqli_query($db, $query);
if (!$result || mysqli_num_rows($result) === 0) exit;
$nm_pelanggan = mysqli_fetch_assoc($result)['nm_pelanggan'];

// Insert data order
$query = "INSERT INTO tbl_order (id_pelanggan, nm_penerima, telp, provinsi, kota, kode_pos, alamat_pengiriman, catatan, tgl_order, ongkir, total_order) 
          VALUES ('$id_pelanggan', '$nm_pelanggan', '$no_telp', '$provinsi', '$kota', '$kodePos', '$alamat', '$catatan', '$tgl_order', '$ongkir', '$total_order')";
if (!mysqli_query($db, $query)) exit;
$id_order = mysqli_insert_id($db);

// Insert data order detail dan update stok
foreach ($data['items'] as $item) {
    $id_produk = mysqli_real_escape_string($db, $item['id_produk']);
    $jumlah = (int) $item['jumlah'];

    $query = "SELECT nm_produk, harga, berat FROM tbl_produk WHERE id_produk = '$id_produk'";
    $result = mysqli_query($db, $query);
    if (!$result || mysqli_num_rows($result) === 0) exit;

    $product = mysqli_fetch_assoc($result);
    $subharga = $product['harga'] * $jumlah;
    $subberat = $product['berat'] * $jumlah;

    mysqli_query($db, "INSERT INTO tbl_detail_order (id_order, id_produk, nm_produk, harga, jml_order, berat, subberat, subharga) 
                       VALUES ('$id_order', '$id_produk', '{$product['nm_produk']}', '{$product['harga']}', '$jumlah', '{$product['berat']}', '$subberat', '$subharga')");
    mysqli_query($db, "UPDATE tbl_produk SET stok = stok - $jumlah WHERE id_produk = '$id_produk'");
}

// Sukses
echo json_encode(['status' => 'success', 'order_id' => $id_order, 'total_order' => $total_order]);
//di pakai
?>
