<?php
// Include file koneksi ke database
require_once "../koneksi.php";

// Set header agar bisa diakses oleh client
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Response array awal
$response = array();

// Pastikan metode adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit();
}

// Ambil data POST
$id = $_POST['id'] ?? '';
$nama = $_POST['nama'] ?? '';
$bank = $_POST['nmBank'] ?? '';
$jml = $_POST['jml_transfer'] ?? '';
$gambarBase64 = $_POST['gambar'] ?? '';

// Validasi awal
if (!$id || !$nama || !$bank || !$jml || !$gambarBase64) {
    $response['status'] = 'error';
    $response['message'] = 'Data tidak lengkap, mohon lengkapi semua field.';
    echo json_encode($response);
    exit();
}

// Ambil total order dari database
$query = "SELECT total_order FROM tbl_order WHERE id_order='$id'";
$result = mysqli_query($db, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    $response['status'] = 'error';
    $response['message'] = 'Order tidak ditemukan.';
    echo json_encode($response);
    exit();
}

// Ambil data order
$data = mysqli_fetch_assoc($result);
$totalOrder = $data['total_order'];

// Validasi jumlah transfer
if ((int) $jml !== (int) $totalOrder) {
    $response['status'] = 'error';
    $response['message'] = 'Jumlah yang Anda transfer tidak sesuai dengan total order.';
    echo json_encode($response);
    exit();
}

// Proses penyimpanan bukti transfer (base64 ke file)
$fileName = uniqid('bukti_', true) . '.png';
$uploadDir = '../../fe/src/assets/img/bukti-transfer/';
$filePath = $uploadDir . $fileName;

// Pastikan folder tujuan ada
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Konversi base64 ke file
if (!file_put_contents($filePath, base64_decode($gambarBase64))) {
    $response['status'] = 'error';
    $response['message'] = 'Gagal menyimpan bukti transfer.';
    echo json_encode($response);
    exit();
}

// Insert data pembayaran ke database
$tgl = date('Y-m-d');
$queryInsert = "INSERT INTO tbl_pembayaran (id_order, nm_pembayar, nm_bank, jml_pembayaran, tgl_bayar, bukti_transfer)
                VALUES ('$id', '$nama', '$bank', '$jml', '$tgl', '$fileName')";

if (mysqli_query($db, $queryInsert)) {
    // Update status order jadi "Sudah Dibayar"
    $queryUpdate = "UPDATE tbl_order SET status='Sudah Dibayar' WHERE id_order='$id'";
    mysqli_query($db, $queryUpdate);

    $response['status'] = 'success';
    $response['message'] = 'Pembayaran berhasil dikonfirmasi. Pesanan Anda segera diproses.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Terjadi kesalahan saat menyimpan data pembayaran.';
}

// Tutup koneksi database
mysqli_close($db);

// Kirim response JSON
echo json_encode($response);
