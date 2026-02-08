<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Http;

try {
    // Basic curl implementation since we don't have full app context loaded for Http facade easily without bootstrapping
    $ch = curl_init();
    $url = "https://hr4.microfinancial-1.com/allemployees";
    $apiKey = "b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a";
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-API-Key: $apiKey",
        "Accept: application/json"
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        echo "Error: " . curl_error($ch) . "\n";
    }
    
    curl_close($ch);
    
    echo "HTTP Status: $httpCode\n";
    $data = json_decode($response, true);
    
    if ($data) {
        echo "Response keys: " . implode(", ", array_keys($data)) . "\n";
        if (isset($data['data'])) {
            echo "First item: " . print_r($data['data'][0], true) . "\n";
        } elseif (is_array($data) && count($data) > 0) {
             echo "First item (direct array): " . print_r($data[0], true) . "\n";
        }
    } else {
        echo "Raw response: " . substr($response, 0, 500) . "\n";
    }

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
