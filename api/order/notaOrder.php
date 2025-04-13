<?php
require_once "../koneksi.php";

header("Access-Control-Allow-Origin: *"); // Mengizinkan akses dari semua domain
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Izinkan metode GET dan POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu
header('Content-Type: application/json');

// Menyiapkan response array
$response = array();

// Memeriksa apakah ID order ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mengambil data order berdasarkan ID
    $query = "SELECT * FROM tbl_order WHERE id_order='$id'";
    $result = mysqli_query($db, $query);

    // Memeriksa apakah ada data yang ditemukan
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        // Menyiapkan data order dalam format array
        $order = array(
            'id_order' => $data['id_order'],
            'nm_penerima' => $data['nm_penerima'],
            'telp' => $data['telp'],
            'alamat_pengiriman' => $data['alamat_pengiriman'],
            'catatan' => $data['catatan'],
            'tgl_order' => date("F d, Y", strtotime($data['tgl_order'])),
            'ongkir' => $data['ongkir'],
            'total_order' => $data['total_order']
        );

        // Mengambil detail order (produk yang dipesan)
        $order_details = array();
        $query_detail = "SELECT d.id_order, p.nm_produk, d.subharga, d.jml_order, p.harga, p.gambar FROM tbl_detail_order d 
                         JOIN tbl_produk p ON d.id_produk = p.id_produk WHERE d.id_order = '$id'";

        $result_detail = mysqli_query($db, $query_detail);
        while ($detail = mysqli_fetch_assoc($result_detail)) {
            $order_details[] = array(
                'nm_produk' => $detail['nm_produk'],
                'harga' => $detail['harga'],
                'subharga' => $detail['subharga'],
                'jml_order' => $detail['jml_order'],
                'gambar' => $detail['gambar']
            );
        }

        // Menambahkan rincian order ke dalam response
        $response['status'] = 'success';
        $response['order'] = $order;
        $response['details'] = $order_details;
    } else {
        // Jika tidak ada data order yang ditemukan
        $response['status'] = 'error';
        $response['message'] = 'Order tidak ditemukan';
    }
} else {
    // Jika ID tidak diberikan
    $response['status'] = 'error';
    $response['message'] = 'ID Order harus diberikan';
}

// Mengirimkan respon dalam format JSON
echo json_encode($response);

// Menutup koneksi database
mysqli_close($db);
?>