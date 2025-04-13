<?php
// Konfigurasi API RajaOngkir
define('RAJAONGKIR_API_KEY', 'ac114346a1e7dbe659a1dc9967bf0477');
define('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter');

// Konfigurasi Header untuk mengatasi CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Fungsi untuk mengirim request ke API RajaOngkir
function sendRequest($endpoint, $method = 'GET', $data = [])
{
    $url = RAJAONGKIR_BASE_URL . $endpoint;

    $headers = [
        "key: " . RAJAONGKIR_API_KEY,
        "Content-Type: application/x-www-form-urlencoded"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Menentukan endpoint yang dipanggil
$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : null;

if ($endpoint === 'provinces') {
    // Mendapatkan daftar provinsi
    echo json_encode(sendRequest('/province'));
} elseif ($endpoint === 'cities') {
    // Mendapatkan daftar kota berdasarkan provinsi
    $provinceId = isset($_GET['province']) ? $_GET['province'] : null;
    $apiUrl = $provinceId ? "/city?province={$provinceId}" : "/city";
    echo json_encode(sendRequest($apiUrl));
} elseif ($endpoint === 'ongkir' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Menghitung ongkir menggunakan method GET ke API lokal
    $origin = isset($_GET['origin']) ? $_GET['origin'] : null;
    $destination = isset($_GET['destination']) ? $_GET['destination'] : null;
    $weight = isset($_GET['weight']) ? $_GET['weight'] : null;
    $courier = isset($_GET['courier']) ? $_GET['courier'] : null;

    if (!$origin || !$destination || !$weight || !$courier) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid input"]);
        exit;
    }

    // Kirim request ke API pusat menggunakan method POST
    echo json_encode(sendRequest('/cost', 'POST', [
        'origin' => $origin,
        'destination' => $destination,
        'weight' => $weight,
        'courier' => $courier
    ]));
} else {
    // Jika endpoint tidak ditemukan
    http_response_code(404);
    echo json_encode(["message" => "Endpoint not found"]);
}
?>