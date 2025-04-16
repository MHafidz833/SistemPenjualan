<?php

require_once "../koneksi.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

$id = $_GET['id_pelanggan'] ?? $_POST['id_pelanggan'] ?? null;
if (!$id) exit;

$query = "SELECT * FROM tbl_order WHERE id_pelanggan='" . intval($id) . "'";
$result = mysqli_query($db, $query);

if (mysqli_num_rows($result) > 0) {
    $orders = [];

    while ($data = mysqli_fetch_assoc($result)) {
        $id_order = $data['id_order'];

        $query2 = "SELECT SUM(jml_order) AS jml FROM tbl_detail_order WHERE id_order='$id_order'";
        $result2 = mysqli_query($db, $query2);
        $jumlah_produk = mysqli_fetch_assoc($result2)['jml'] ?? 0;

        $orders[] = [
            'id_order' => $id_order,
            'tanggal' => date("F d, Y", strtotime($data['tgl_order'])),
            'jumlah_produk' => $jumlah_produk,
            'status' => $data['status'],
            'total_harga' => $data['total_order'],
            'no_resi' => $data['no_resi'],
            'action_url' => getActionUrl($data['status'], $id_order)
        ];
    }

    echo json_encode(['status' => 'success', 'data' => $orders]);
} else {
    echo json_encode(['status' => 'error']);
}

function getActionUrl($status, $id_order) {
    return in_array($status, ['Belum Dibayar', 'Sudah Dibayar', 'Menyiapkan Produk', 'Produk Dikirim', 'Produk Diterima'])
        ? "nota-order.php?id=$id_order"
        : "#";
}
//dipakai
?>
