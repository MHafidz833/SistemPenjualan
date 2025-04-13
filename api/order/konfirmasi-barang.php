<?php
require_once "../koneksi.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID order diperlukan!']);
    exit();
}

$query = "UPDATE tbl_order SET status='Produk Diterima' WHERE id_order='$id'";
$result = mysqli_query($db, $query);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'Pesanan telah dikonfirmasi sebagai diterima']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status pesanan']);
}
?>
