<?php
session_start(); // Ini WAJIB kalau mau pakai session

require "../../koneksi.php";

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

// Ambil data dashboard
$data = [];

// Total Pendapatan
$q3 = "SELECT SUM(jml_pembayaran) as jml FROM tbl_pembayaran";
$res3 = mysqli_query($db, $q3);
$dta3 = mysqli_fetch_assoc($res3);
$data['total_pendapatan'] = $dta3['jml'] ?? 0;

// Total Member
$q4 = "SELECT COUNT(id_pelanggan) as jml FROM tbl_pelanggan";
$res4 = mysqli_query($db, $q4);
$dta4 = mysqli_fetch_assoc($res4);
$data['total_member'] = $dta4['jml'] ?? 0;

// Total Produk
$q5 = "SELECT SUM(stok) as jml FROM tbl_produk";
$res5 = mysqli_query($db, $q5);
$dta5 = mysqli_fetch_assoc($res5);
$data['total_produk'] = $dta5['jml'] ?? 0;

// Total Artikel
$q6 = "SELECT COUNT(id_pos) as jml FROM tbl_pos";
$res6 = mysqli_query($db, $q6);
$dta6 = mysqli_fetch_assoc($res6);
$data['total_artikel'] = $dta6['jml'] ?? 0;

// Stok Produk per Kategori
$stok = [];
$q = "SELECT nm_kategori, SUM(stok) as jml 
      FROM tbl_produk a 
      JOIN tbl_kat_produk b ON a.id_kategori = b.id_kategori 
      GROUP BY nm_kategori";
$res = mysqli_query($db, $q);
while ($row = mysqli_fetch_assoc($res)) {
    $stok[] = [
        'kategori' => $row['nm_kategori'],
        'stok' => $row['jml']
    ];
}
$data['stok_produk'] = $stok;

// Return JSON
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
echo json_encode($data);
