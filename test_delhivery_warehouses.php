<?php
// Check registered warehouses in Delhivery

echo "\n🏭 CHECKING DELHIVERY WAREHOUSES\n";
echo "================================\n\n";

$apiKey = '8e0534aa9d751cad7d08527493cec1f0541c7bd1';

// Test both staging and production
$environments = [
    'staging' => 'https://staging-express.delhivery.com',
    'production' => 'https://track.delhivery.com'
];

foreach ($environments as $env => $baseUrl) {
    echo strtoupper($env) . " Environment:\n";
    echo "URL: $baseUrl\n\n";
    
    // Try to get warehouse list
    echo "1. Checking warehouse endpoint...\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/backend/clientwarehouse/json/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Token ' . $apiKey,
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "   Status: $httpCode\n";
    
    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if ($data && isset($data['data'])) {
            echo "   ✅ Found " . count($data['data']) . " warehouse(s)\n\n";
            foreach ($data['data'] as $warehouse) {
                echo "   Warehouse Details:\n";
                echo "   - Name: " . ($warehouse['name'] ?? 'N/A') . "\n";
                echo "   - Registered Name: " . ($warehouse['registered_name'] ?? 'N/A') . "\n";
                echo "   - Pin: " . ($warehouse['pin'] ?? 'N/A') . "\n";
                echo "   - City: " . ($warehouse['city'] ?? 'N/A') . "\n";
                echo "   - Address: " . ($warehouse['address'] ?? 'N/A') . "\n";
                echo "   - Phone: " . ($warehouse['phone'] ?? 'N/A') . "\n";
                echo "   - Status: " . ($warehouse['status'] ?? 'N/A') . "\n";
                echo "   " . str_repeat("-", 40) . "\n";
            }
        } else {
            echo "   Response:\n";
            print_r($data);
        }
    } else {
        echo "   ❌ Could not retrieve warehouse list\n";
        echo "   Response: " . substr($response, 0, 200) . "\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n\n";
}

echo "📌 NOTE: Use the exact 'name' value from above in your pickup_location.\n";
echo "If no warehouses are listed, you need to register one with Delhivery.\n";