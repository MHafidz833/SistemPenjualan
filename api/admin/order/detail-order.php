<?php
session_start();
require "../../koneksi.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Validasi parameter id
$id_order = $_GET['id'] ?? '';
if (!$id_order) {
    echo json_encode(['message' => 'Parameter "id" dibutuhkan']);
    exit;
}

// Ambil data order dan pelanggan
$query = "SELECT * FROM tbl_order o 
          JOIN tbl_pelanggan p ON o.id_pelanggan = p.id_pelanggan 
          WHERE id_order = '$id_order'";
$result = mysqli_query($db, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(['message' => 'Data order tidak ditemukan']);
    exit;
}

$data = mysqli_fetch_assoc($result);
$tgl_order = $data['tgl_order'];
$status = $data['status'];

// Ambil ongkir dan total
$orderResult = mysqli_query($db, "SELECT ongkir, total_order FROM tbl_order WHERE id_order = '$id_order'");
$orderData = mysqli_fetch_assoc($orderResult);
$ongkir = $orderData['ongkir'] ?? 0;
$total_order = $orderData['total_order'] ?? 0;

// Ambil detail produk
$detailResult = mysqli_query($db, "
    SELECT p.gambar, p.nm_produk, p.harga, d.jml_order, d.subharga 
    FROM tbl_detail_order d 
    JOIN tbl_produk p ON d.id_produk = p.id_produk 
    WHERE d.id_order = '$id_order'
");

$products = [];
$subtotal = 0;

while ($row = mysqli_fetch_assoc($detailResult)) {
    $products[] = [
        'gambar' => $row['gambar'],
        'nm_produk' => $row['nm_produk'],
        'harga' => (int)$row['harga'],
        'jml_order' => (int)$row['jml_order'],
        'subharga' => (int)$row['subharga']
    ];
    $subtotal += $row['subharga'];
}

// Format response JSON
$response = [
    'pelanggan' => [
        'nm_pelanggan' => $data['nm_pelanggan'],
        'email' => $data['email']
    ],
    'alamat_penerima' => [
        'nm_penerima' => $data['nm_penerima'],
        'alamat_pengiriman' => $data['alamat_pengiriman'],
        'kode_pos' => $data['kode_pos']
    ],
    'catatan' => $data['catatan'],
    'tanggal_order' => date("F d, Y", strtotime($tgl_order)),
    'status' => [
        'status' => $status,
        'badge' => get_status_badge($status, $data['no_resi'])
    ],
    'products' => $products,
    'subtotal' => $subtotal,
    'shipping' => $ongkir,
    'total_order' => $total_order
];

echo json_encode($response);

// Fungsi badge status
function get_status_badge($status, $no_resi = null) {
    switch ($status) {
        case 'Belum Dibayar':
            return ['badge' => 'warning', 'residue' => null];
        case 'Sudah Dibayar':
            return ['badge' => 'secondary', 'residue' => null];
        case 'Menyiapkan Produk':
            return ['badge' => 'info', 'residue' => null];
        case 'Produk Dikirim':
            return ['badge' => 'danger', 'residue' => $no_resi ? "Resi: $no_resi" : null];
        case 'Produk Diterima':
            return ['badge' => 'success', 'residue' => null];
        default:
            return ['badge' => 'default', 'residue' => null];
    }
}
?>
