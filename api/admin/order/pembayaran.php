<?php
// Menetapkan header JSON
require "../../koneksi.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS"); // Izinkan metode GET, POST, DELETE, PUT
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu

// Memulai sesi dan menghubungkan ke database
session_start();

// Fungsi untuk mendapatkan data pembayaran berdasarkan id_order
function getPaymentDetails($id_order, $db)
{
    $query = "SELECT * FROM tbl_pembayaran WHERE id_order='$id_order'";
    $result = mysqli_query($db, $query);
    return $result ? mysqli_fetch_assoc($result) : null;
}

// Fungsi untuk mengubah status order dan nomor resi
function updateOrderStatus($id_order, $status, $resi, $db)
{
    $query = "UPDATE tbl_order SET status='$status', no_resi='$resi' WHERE id_order='$id_order'";
    return mysqli_query($db, $query);
}

// Mengambil id_order dari URL parameter
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_order = mysqli_real_escape_string($db, $_GET['id']);
    $paymentDetails = getPaymentDetails($id_order, $db);

    if ($paymentDetails) {
        $tgl_bayar = date("d/m/Y", strtotime($paymentDetails['tgl_bayar']));
        echo json_encode([
            'status' => 'success',
            'payment_details' => [
                'bukti_transfer' => $paymentDetails['bukti_transfer'],
                'nm_pembayar' => $paymentDetails['nm_pembayar'],
                'nm_bank' => $paymentDetails['nm_bank'],
                'tgl_bayar' => $tgl_bayar,
                'jml_pembayaran' => number_format($paymentDetails['jml_pembayaran'])
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Payment details not found for the given order ID']);
    }
    exit;
}

// Mengubah status order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data JSON dari request
    $input = json_decode(file_get_contents("php://input"), true);

    // Memastikan parameter yang dibutuhkan ada
    if (!isset($input['id_order']) || !isset($input['status'])) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
        exit;
    }

    $id_order = mysqli_real_escape_string($db, $input['id_order']);
    $status = mysqli_real_escape_string($db, $input['status']);
    $resi = isset($input['resi']) ? mysqli_real_escape_string($db, $input['resi']) : '';

    // Jika status "Produk Dikirim", resi harus diisi
    if ($status === 'Produk Dikirim' && empty($resi)) {
        echo json_encode(['status' => 'error', 'message' => 'Resi wajib diisi saat produk dikirim.']);
        exit;
    }

    // Jika status bukan "Produk Dikirim", resi dikosongkan
    if ($status !== 'Produk Dikirim') {
        $resi = '';
    }

    // Memperbarui status order
    if (updateOrderStatus($id_order, $status, $resi, $db)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Order status updated successfully',
            'data' => [
                'id_order' => $id_order,
                'status' => $status,
                'resi' => $resi
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update order status']);
    }
    exit;
}
?>