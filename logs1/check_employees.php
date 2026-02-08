<?php
require __DIR__ . '/vendor/autoload.php';

try {
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
    curl_close($ch);
    
    $data = json_decode($response, true);
    $employees = $data['data'] ?? $data;
    
    $found = [];
    $allowed = ['Cleaning Staff', 'Technician', 'Mechanic'];
    
    foreach ($employees as $emp) {
        $title = $emp['job']['job_title'] ?? ($emp['position']['department'] ?? 'N/A');
        // echo "Checking: " . $title . "\n";
        if (in_array($title, $allowed)) {
            $found[] = [
                'name' => $emp['full_name'],
                'title' => $title
            ];
        }
    }
    
    echo "Found " . count($found) . " matching employees:\n";
    print_r($found);

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
