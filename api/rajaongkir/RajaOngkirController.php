<?php
define('RAJAONGKIR_API_KEY', 'ac114346a1e7dbe659a1dc9967bf0477');
define('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter');

class RajaOngkirController {
    
    // Fungsi untuk mengirim request ke API RajaOngkir
    private function sendRequest($endpoint, $method = 'GET', $data = []) {
        $url = RAJAONGKIR_BASE_URL . $endpoint;

        $headers = [
            "key: " . RAJAONGKIR_API_KEY,
            "Content-Type: application/x-www-form-urlencoded"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    // **Mendapatkan daftar provinsi**
    public function getProvinces() {
        return $this->sendRequest('/province');
    }

    // **Mendapatkan daftar kota berdasarkan provinsi**
    public function getCities($provinceId = null) {
        $endpoint = $provinceId ? "/city?province={$provinceId}" : "/city";
        return $this->sendRequest($endpoint);
    }

    // **Menghitung ongkir**
    public function calculateOngkir($origin, $destination, $weight, $courier) {
        return $this->sendRequest('/cost', 'POST', [
            'origin'      => $origin,
            'destination' => $destination,
            'weight'      => $weight,
            'courier'     => $courier
        ]);
    }
}
?>
