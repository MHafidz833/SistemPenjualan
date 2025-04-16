<?php
session_start();
require "../../koneksi.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

function getPaymentDetails($id_order, $db) {
    $query = "SELECT * FROM tbl_pembayaran WHERE id_order='$id_order'";
    return mysqli_fetch_assoc(mysqli_query($db, $query)) ?: null;
}

function updateOrderStatus($id_order, $status, $resi, $db) {
    return mysqli_query($db, "UPDATE tbl_order SET status='$status', no_resi='$resi' WHERE id_order='$id_order'");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id_order = mysqli_real_escape_string($db, $_GET['id']);
    $paymentDetails = getPaymentDetails($id_order, $db);

    echo json_encode($paymentDetails ? [
        'status' => 'success',
        'payment_details' => [
            'bukti_transfer' => $paymentDetails['bukti_transfer'],
            'nm_pembayar' => $paymentDetails['nm_pembayar'],
            'nm_bank' => $paymentDetails['nm_bank'],
            'tgl_bayar' => date("d/m/Y", strtotime($paymentDetails['tgl_bayar'])),
            'jml_pembayaran' => number_format($paymentDetails['jml_pembayaran'])
        ]
    ] : ['status' => 'error', 'message' => 'Payment details not found']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['id_order']) || !isset($input['status'])) exit(json_encode(['status' => 'error', 'message' => 'Missing parameters']));

    $id_order = mysqli_real_escape_string($db, $input['id_order']);
    $status = mysqli_real_escape_string($db, $input['status']);
    $resi = $status === 'Produk Dikirim' ? mysqli_real_escape_string($db, $input['resi'] ?? '') : '';

    if ($status === 'Produk Dikirim' && empty($resi)) exit(json_encode(['status' => 'error', 'message' => 'Resi wajib diisi saat produk dikirim.']));

    echo json_encode(updateOrderStatus($id_order, $status, $resi, $db) ? [
        'status' => 'success',
        'message' => 'Order status updated',
        'data' => ['id_order' => $id_order, 'status' => $status, 'resi' => $resi]
    ] : ['status' => 'error', 'message' => 'Failed to update order status']);
    exit;
}
//dipake
?>
