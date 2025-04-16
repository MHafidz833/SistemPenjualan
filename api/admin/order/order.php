<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require '../../koneksi.php';

$action = $_GET['action'] ?? '';

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
    echo json_encode(mysqli_fetch_assoc($result));
}

function getOrders($db) {
    $result = mysqli_query($db, "SELECT * FROM tbl_order ORDER BY tgl_order DESC");
    echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC));
}

function getOrderById($db) {
    $id_order = $_GET['id_order'] ?? 0;
    if (!$id_order) {
        echo json_encode(["message" => "ID order tidak ditemukan"]);
        return;
    }

    $result = mysqli_query($db, "SELECT * FROM tbl_order WHERE id_order = '$id_order'");
    echo json_encode(mysqli_fetch_assoc($result) ?: ["message" => "Order tidak ditemukan"]);
}

function addOrder($db) {
    $nm_penerima = $_POST['nm_penerima'] ?? '';
    $tgl_order = $_POST['tgl_order'] ?? '';
    $total_order = $_POST['total_order'] ?? 0;
    $status = $_POST['status'] ?? 'Belum Dibayar';

    if (!$nm_penerima || !$tgl_order || $total_order <= 0) {
        echo json_encode(["message" => "Data order tidak lengkap"]);
        return;
    }

    $query = "INSERT INTO tbl_order (nm_penerima, tgl_order, total_order, status) 
              VALUES ('$nm_penerima', '$tgl_order', '$total_order', '$status')";
    
    echo json_encode(["message" => mysqli_query($db, $query) ? "Order berhasil ditambahkan" : "Gagal menambahkan order"]);
}

function updateOrder($db) {
    $id_order = $_POST['id_order'] ?? 0;
    $status = $_POST['status'] ?? '';

    if (!$id_order || !$status) {
        echo json_encode(["message" => "Data tidak valid"]);
        return;
    }

    $query = "UPDATE tbl_order SET status = '$status' WHERE id_order = '$id_order'";
    
    echo json_encode(["message" => mysqli_query($db, $query) ? "Order berhasil diperbarui" : "Gagal memperbarui order"]);
}
//dipakai
?>
