<?php
require "../../koneksi.php";
// Memulai sesi dan menghubungkan ke database
session_start();

header('Content-Type: application/json');
// Membuat koneksi ke database

// Mendapatkan semua order
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Ambil semua data order dari database
    $orders = $db->query("SELECT * FROM tbl_order");

    if ($orders->num_rows > 0) {
        $order_data = [];
        while ($order = $orders->fetch_assoc()) {
            $order_data[] = $order;
        }
        // Mengembalikan data order dalam format JSON
        echo json_encode(['tbl_order' => $order_data]);
    } else {
        echo json_encode(['error' => 'No orders found']);
    }
}

// Mengupdate status order
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_order'])) {
    if (isset($_POST['order_id']) && isset($_POST['status'])) {
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];

        // Validasi status yang diterima
        if (in_array($status, ['Pending', 'Delivered', 'Cancelled'])) {
            // Update status order
            $stmt = $db->prepare("UPDATE tbl_order SET status = ? WHERE id_order = ?");
            $stmt->bind_param('si', $status, $order_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => 'Order status updated successfully']);
            } else {
                echo json_encode(['error' => 'Failed to update order status']);
            }
        } else {
            echo json_encode(['error' => 'Invalid status']);
        }
    } else {
        echo json_encode(['error' => 'Missing parameters']);
    }
}

// Menghapus order
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_order'])) {
    if (isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];

        // Hapus order dari database
        $stmt = $db->prepare("DELETE FROM tbl_order WHERE id_order = ?");
        $stmt->bind_param('i', $order_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Order deleted successfully']);
        } else {
            echo json_encode(['error' => 'Failed to delete order']);
        }
    } else {
        echo json_encode(['error' => 'Missing order_id']);
    }
}

?>