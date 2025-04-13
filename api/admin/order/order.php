<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require '../../koneksi.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'getStats':
        getStats($db);
        break;
    case 'getOrders':
        getOrders($db);
        break;
    case 'getOrderById':
        getOrderById($db);
        break;
    case 'addOrder':
        addOrder($db);
        break;
    case 'updateOrder':
        updateOrder($db);
        break;
    case 'deleteOrder':
        deleteOrder($db);
        break;
    default:
        echo json_encode(["message" => "Invalid API action"]);
}

function getStats($db) {
    $query = "SELECT 
        (SELECT COUNT(*) FROM tbl_order) AS totalOrder,
        (SELECT COUNT(*) FROM tbl_order WHERE status='Belum Dibayar') AS blmDibayar,
        (SELECT COUNT(*) FROM tbl_order WHERE status='Sudah Dibayar') AS sudahDibayar,
        (SELECT COUNT(*) FROM tbl_order WHERE status='Menyiapkan Produk') AS menyiapkanProduk,
        (SELECT COUNT(*) FROM tbl_order WHERE status='Produk Dikirim') AS produkDikirim,
        (SELECT COUNT(*) FROM tbl_order WHERE status='Produk Diterima') AS diterima";

    $result = mysqli_query($db, $query);
    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
}

function getOrders($db) {
    $query = "SELECT * FROM tbl_order ORDER BY tgl_order DESC";
    $result = mysqli_query($db, $query);
    $orders = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }

    echo json_encode($orders);
}

function getOrderById($db) {
    $id_order = $_GET['id_order'] ?? 0;
    if ($id_order == 0) {
        echo json_encode(["message" => "ID order tidak ditemukan"]);
        return;
    }

    $query = "SELECT * FROM tbl_order WHERE id_order = '$id_order'";
    $result = mysqli_query($db, $query);
    $order = mysqli_fetch_assoc($result);

    echo json_encode($order ? $order : ["message" => "Order tidak ditemukan"]);
}

function addOrder($db) {
    $nm_penerima = $_POST['nm_penerima'] ?? '';
    $tgl_order = $_POST['tgl_order'] ?? '';
    $total_order = $_POST['total_order'] ?? 0;
    $status = $_POST['status'] ?? 'Belum Dibayar';

    if (empty($nm_penerima) || empty($tgl_order) || $total_order <= 0) {
        echo json_encode(["message" => "Data order tidak lengkap"]);
        return;
    }

    $query = "INSERT INTO tbl_order (nm_penerima, tgl_order, total_order, status) 
              VALUES ('$nm_penerima', '$tgl_order', '$total_order', '$status')";
    
    if (mysqli_query($db, $query)) {
        echo json_encode(["message" => "Order berhasil ditambahkan"]);
    } else {
        echo json_encode(["message" => "Gagal menambahkan order"]);
    }
}

function updateOrder($db) {
    $id_order = $_POST['id_order'] ?? 0;
    $status = $_POST['status'] ?? '';

    if ($id_order == 0 || empty($status)) {
        echo json_encode(["message" => "Data tidak valid"]);
        return;
    }

    $query = "UPDATE tbl_order SET status = '$status' WHERE id_order = '$id_order'";
    
    if (mysqli_query($db, $query)) {
        echo json_encode(["message" => "Order berhasil diperbarui"]);
    } else {
        echo json_encode(["message" => "Gagal memperbarui order"]);
    }
}

function deleteOrder($db) {
    $id_order = $_POST['id_order'] ?? 0;
    if ($id_order == 0) {
        echo json_encode(["message" => "ID order tidak ditemukan"]);
        return;
    }

    $query = "DELETE FROM tbl_order WHERE id_order = '$id_order'";
    
    if (mysqli_query($db, $query)) {
        echo json_encode(["message" => "Order berhasil dihapus"]);
    } else {
        echo json_encode(["message" => "Gagal menghapus order"]);
    }
}
?>
