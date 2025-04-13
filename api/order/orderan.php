<?php
// Include header untuk koneksi database
require_once "../koneksi.php";

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

$id = isset($_GET['id_pelanggan']) ? intval($_GET['id_pelanggan']) : (isset($_POST['id_pelanggan']) ? intval($_POST['id_pelanggan']) : null);

if (!$id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID pelanggan diperlukan!'
    ]);
    exit();
}

// Query mengambil order termasuk `no_resi`
$query = "SELECT * FROM tbl_order WHERE id_pelanggan='$id'";
$result = mysqli_query($db, $query);

$response = [];

if (mysqli_num_rows($result) > 0) {
    $orders = [];

    while ($data = mysqli_fetch_assoc($result)) {
        $id_order = $data['id_order'];
        $tgl = $data['tgl_order'];
        $status = $data['status'];
        $no_resi = $data['no_resi']; // Tambahkan ini

        // Ambil jumlah produk
        $query2 = "SELECT SUM(jml_order) AS jml FROM tbl_detail_order WHERE id_order='$id_order'";
        $result2 = mysqli_query($db, $query2);
        $data2 = mysqli_fetch_assoc($result2);

        $orders[] = [
            'id_order' => $id_order,
            'tanggal' => date("F d, Y", strtotime($tgl)),
            'jumlah_produk' => $data2['jml'],
            'status' => $status,
            'total_harga' => $data['total_order'],
            'no_resi' => $no_resi, // Kirim juga ke frontend
            'action_url' => getActionUrl($status, $id_order)
        ];
    }

    $response = [
        'status' => 'success',
        'message' => 'Data orderan ditemukan',
        'data' => $orders
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Orderan Kosong, Silahkan Melakukan Pembelian Dulu!'
    ];
}

echo json_encode($response);

function getActionUrl($status, $id_order)
{
    switch ($status) {
        case 'Belum Dibayar':
            return "konfirmasi-pembayaran.php?id=$id_order";
        case 'Sudah Dibayar':
        case 'Menyiapkan Produk':
        case 'Produk Dikirim':
        case 'Produk Diterima':
            return "nota-order.php?id=$id_order";
        default:
            return "#";
    }
}

// Proses hapus order (tidak diubah)
if (isset($_GET['delete'])) {
    $orderId = intval($_GET['delete']);

    $queryDelete = "SELECT * FROM tbl_order WHERE id_order='$orderId' AND id_pelanggan='$id'";
    $resultDelete = mysqli_query($db, $queryDelete);

    if (mysqli_num_rows($resultDelete) > 0) {
        $deleteQuery = "DELETE FROM tbl_order WHERE id_order = $orderId";
        $deleteResult = mysqli_query($db, $deleteQuery);

        if ($deleteResult) {
            $response = [
                'status' => 'success',
                'message' => 'Order berhasil dihapus'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Gagal menghapus order'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Order tidak ditemukan atau tidak dapat dihapus'
        ];
    }

    echo json_encode($response);
    exit();
}
?>
