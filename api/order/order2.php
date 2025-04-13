<?php
// order.php
require "../koneksi.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Izinkan metode GET dan POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu
$data = json_decode(file_get_contents("php://input"), true);
print_r($data);

// Mendapatkan data dari $data
$id_pelanggan = $data['id_pelanggan'];
$no_telp = $data['no_telp'];
$provinsi = $data['province_destination'];
$kota = $data['city_destination'];
$kdPos = $data['kodePos'];
$alamat = $data['alamat'];
$catatan = $data['catatan'];
$ongkir_list = $data['ongkir'];
$subtotal_list = $data['subtotal'];
$id_produk_list = $data['id_produk'];
$jumlah_list = $data['jumlah'];

// print_r($id_pelanggan);
// Validasi data wajib
$required_fields = [
    'id_pelanggan',
    'no_telp',
    'province_destination',
    'city_destination',
    'kodePos',
    'alamat',
    'catatan',
    'ongkir',
    'subtotal',
    'id_produk',
    'jumlah'
];

// foreach ($required_fields as $field) {
//     if (empty($$field)) {
//         echo json_encode(['status' => 'error', 'message' => 'Missing required field: ' . $field]);
//         exit;
//     }
// }

// Ambil nama pelanggan dari tabel tbl_pelanggan
$query = "SELECT nm_pelanggan FROM tbl_pelanggan WHERE id_pelanggan='$id_pelanggan'";
$result = mysqli_query($db, $query);
if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Error fetching customer data']);
    exit;
}

$pelanggan = mysqli_fetch_array($result);
if (!$pelanggan) {
    echo json_encode(['status' => 'error', 'message' => 'Customer not found']);
    exit;
}

$nmPenerima = $pelanggan['nm_pelanggan']; // Nama penerima dari tbl_pelanggan

// Hitung total order dan total ongkir
$total_order = 0;
$total_ongkir = 0;

foreach ($id_produk_list as $index => $id_produk) {
    $total_order += $subtotal_list[$index];
    $total_ongkir += $ongkir_list[$index];
}

$tgl_order = date('Y-m-d');

// print_r(gettype($subtotal_list));
// print_r($total_ongkir);

// Insert ke tbl_order dengan total ongkir
$query2 = "INSERT INTO tbl_order (id_pelanggan, nm_penerima, telp, provinsi, kota, kode_pos, alamat_pengiriman, catatan, tgl_order, ongkir, total_order) 
           VALUES ('$id_pelanggan', '$nmPenerima', '$no_telp', '$provinsi', '$kota', '$kdPos', '$alamat', '$catatan', '$tgl_order', '$total_ongkir', '$total_order')";

if (!mysqli_query($db, $query2)) {
    echo json_encode(['status' => 'error', 'message' => 'Error inserting order']);
    exit;
}

// Ambil ID order terakhir
$id_order_barusan = mysqli_insert_id($db);

// Iterasi untuk insert data ke tbl_detail_order
foreach ($id_produk_list as $index => $id_produk) {
    $jumlah = $jumlah_list[$index];         // Ambil jumlah produk
    $subtotal = $subtotal_list[$index];     // Ambil subtotal per produk

    // Ambil data produk
    $query3 = "SELECT * FROM tbl_produk WHERE id_produk='$id_produk'";
    $result3 = mysqli_query($db, $query3);
    if (!$result3) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found for ID: ' . $id_produk]);
        exit;
    }

    $produk = mysqli_fetch_array($result3);
    $nmProduk = $produk['nm_produk'];
    $harga = $produk['harga'];
    $berat = $produk['berat'];
    $subberat = $berat * $jumlah;
    $subharga = $harga * $jumlah; // Subtotal untuk satu jenis produk

    // Insert ke tbl_detail_order
    $query4 = "INSERT INTO tbl_detail_order (id_order, id_produk, nm_produk, harga, jml_order, berat, subberat, subharga) 
               VALUES ('$id_order_barusan', '$id_produk', '$nmProduk', '$harga', '$jumlah', '$berat', '$subberat', '$subharga')";

    if (!mysqli_query($db, $query4)) {
        echo json_encode(['status' => 'error', 'message' => 'Error inserting detail for product ID: ' . $id_produk]);
        exit;
    }

    // Update stok produk
    $query5 = "UPDATE tbl_produk SET stok=stok-$jumlah WHERE id_produk='$id_produk'";
    if (!mysqli_query($db, $query5)) {
        echo json_encode(['status' => 'error', 'message' => 'Error updating stock for product ID: ' . $id_produk]);
        exit;
    }
}

// Berhasil
echo json_encode([
    'status' => 'success',
    'message' => 'Order successfully placed',
    'order_id' => $id_order_barusan
]);
?>