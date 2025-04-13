<?php
// Memulai sesi dan menghubungkan ke database
session_start();
require "../../koneksi.php";

// Mengatur header untuk JSON
header('Content-Type: application/json');

// Mendapatkan id_order dari URL (query parameter)
if (!isset($_GET['id'])) {
	echo json_encode(['error' => 'id parameter is required']);
	exit;
}

$id_order = $_GET['id'];

// Query untuk mendapatkan data order dan pelanggan
$query = "SELECT * FROM tbl_order o JOIN tbl_pelanggan p ON o.id_pelanggan=p.id_pelanggan WHERE id_order='$id_order'";
$result = mysqli_query($db, $query);

// Jika tidak ada data ditemukan
if (mysqli_num_rows($result) == 0) {
	echo json_encode(['error' => 'Order not found']);
	exit;
}

// Mendapatkan data order
$data = mysqli_fetch_assoc($result);

// Mengambil tanggal, status, dan informasi lain
$tgl = $data['tgl_order'];
$status = $data['status'];

// Query untuk mendapatkan ongkir dan total order
$order = "SELECT ongkir, total_order FROM tbl_order WHERE id_order='$id_order'";
$res = mysqli_query($db, $order);
$dta = mysqli_fetch_assoc($res);

// Menghitung subtotal
$subtotal = 0;
$qproduk = "SELECT * FROM tbl_detail_order d JOIN tbl_produk p ON d.id_produk=p.id_produk WHERE id_order = '$id_order'";
$rproduk = mysqli_query($db, $qproduk);

$products = array();
while ($produk = mysqli_fetch_assoc($rproduk)) {
	$products[] = array(
		'gambar' => $produk['gambar'],
		'nm_produk' => $produk['nm_produk'],
		'harga' => $produk['harga'],
		'jml_order' => $produk['jml_order'],
		'subharga' => $produk['subharga']
	);
	$subtotal += $produk['subharga'];
}

// Menyiapkan data untuk dikirim dalam JSON
$response = array(
	'pelanggan' => array(
		'nm_pelanggan' => $data['nm_pelanggan'],
		'email' => $data['email']
	),
	'alamat_penerima' => array(
		'nm_penerima' => $data['nm_penerima'],
		'alamat_pengiriman' => $data['alamat_pengiriman'],
		'kode_pos' => $data['kode_pos']
	),
	'catatan' => $data['catatan'],
	'tanggal_order' => date("F d, Y", strtotime($tgl)),
	'status' => array(
		'status' => $status,
		'badge' => get_status_badge($status, $data['no_resi'])
	),
	'products' => $products,
	'subtotal' => $subtotal,
	'shipping' => $dta['ongkir'],
	'total_order' => $dta['total_order']
);

// Mengirimkan data dalam format JSON
echo json_encode($response);

// Fungsi untuk menentukan badge status order
function get_status_badge($status, $no_resi = null)
{
	switch ($status) {
		case 'Belum Dibayar':
			return array('badge' => 'warning', 'residue' => null);
		case 'Sudah Dibayar':
			return array('badge' => 'secondary', 'residue' => null);
		case 'Menyiapkan Produk':
			return array('badge' => 'info', 'residue' => null);
		case 'Produk Dikirim':
			return array('badge' => 'danger', 'residue' => $no_resi ? "Resi: " . $no_resi : null);
		case 'Produk Diterima':
			return array('badge' => 'success', 'residue' => null);
		default:
			return array('badge' => 'default', 'residue' => null);
	}
}
?>