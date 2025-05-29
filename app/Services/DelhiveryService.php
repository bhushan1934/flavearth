<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DelhiveryService
{
    protected $apiKey;
    protected $clientName;
    protected $baseUrl;
    protected $pickupLocation;

    public function __construct()
    {
        $this->apiKey = config('services.delhivery.api_key');
        $this->clientName = config('services.delhivery.client_name');
        $this->baseUrl = config('services.delhivery.base_url');
        $this->pickupLocation = config('services.delhivery.pickup_location');
    }

    /**
     * Create a new shipment/waybill
     */
    public function createShipment($orderData)
    {
        $url = $this->baseUrl . '/api/cmu/create.json';
        
        $shipmentData = $this->prepareShipmentData($orderData);
        
        Log::info('Attempting to create Delhivery shipment', [
            'order_id' => $orderData['order']->id,
            'url' => $url,
            'pickup_location' => $this->pickupLocation
        ]);
        
        try {
            // Build payload with pickup_location
            $payload = [
                'shipments' => [$shipmentData],
                'pickup_location' => [
                    'name' => $this->pickupLocation,
                    'add' => config('services.delhivery.return_address', 'Flavearth Store'),
                    'city' => config('services.delhivery.return_city', 'New Delhi'),
                    'pin_code' => config('services.delhivery.return_pin', '110001'),
                    'country' => 'India',
                    'phone' => config('services.delhivery.return_phone', '9999999999')
                ]
            ];
            
            Log::debug('Delhivery API payload', ['payload' => $payload]);
            
            // Delhivery requires form data with specific format
            $response = Http::asForm()->withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
                'Accept' => 'application/json'
            ])->post($url, [
                'format' => 'json',
                'data' => json_encode($payload)
            ]);

            $responseData = $response->json();
            
            // Check if the response indicates success even with the error message
            if ($response->successful() && isset($responseData['packages']) && !empty($responseData['packages'])) {
                Log::info('Delhivery shipment created successfully', $responseData);
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else if ($response->successful() && isset($responseData['rmk']) && strpos($responseData['rmk'], 'Package might be saved') !== false) {
                // Handle the case where package might have been created despite the error
                Log::warning('Delhivery API returned error but package might be saved', $responseData);
                
                // For now, we'll treat this as a failure until we can verify with Delhivery
                return [
                    'success' => false,
                    'error' => 'Delhivery API error: ' . $responseData['rmk'],
                    'response' => $responseData,
                    'needs_support' => true
                ];
            } else {
                Log::error('Delhivery shipment creation failed', [
                    'status' => $response->status(),
                    'response' => $responseData,
                    'body' => $response->body()
                ]);
                
                $errorMessage = 'Failed to create shipment';
                if (isset($responseData['error'])) {
                    $errorMessage = is_array($responseData['error']) 
                        ? json_encode($responseData['error']) 
                        : $responseData['error'];
                } elseif (isset($responseData['message'])) {
                    $errorMessage = $responseData['message'];
                }
                
                return [
                    'success' => false,
                    'error' => $errorMessage,
                    'response' => $responseData
                ];
            }
        } catch (\Exception $e) {
            Log::error('Delhivery API exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'error' => 'API request failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Track shipment by waybill number
     */
    public function trackShipment($waybill)
    {
        $url = $this->baseUrl . '/api/v1/packages/json/';
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
                'Accept' => 'application/json'
            ])->get($url, [
                'waybill' => $waybill
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to track shipment'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Delhivery tracking error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Tracking request failed'
            ];
        }
    }

    /**
     * Cancel shipment
     */
    public function cancelShipment($waybill)
    {
        $url = $this->baseUrl . '/api/p/edit';
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($url, [
                'waybill' => $waybill,
                'cancellation' => true
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to cancel shipment'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Delhivery cancellation error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Cancellation request failed'
            ];
        }
    }

    /**
     * Generate packing slip
     */
    public function generatePackingSlip($waybills)
    {
        $url = $this->baseUrl . '/api/p/packing_slip';
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
            ])->get($url, [
                'wbns' => implode(',', (array) $waybills),
                'pdf' => 'true'
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'pdf' => $response->body()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to generate packing slip'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Delhivery packing slip error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Failed to generate packing slip'
            ];
        }
    }

    /**
     * Prepare shipment data according to Delhivery format
     */
    protected function prepareShipmentData($orderData)
    {
        $order = $orderData['order'];
        $shippingAddress = is_string($order->shipping_address) 
            ? json_decode($order->shipping_address, true) 
            : $order->shipping_address;
        
        // Calculate total weight (in grams)
        $totalWeight = 500; // Default 500g for all orders
        
        // Format phone number (remove +91 if present, ensure 10 digits)
        $phone = preg_replace('/^\+91/', '', $shippingAddress['phone']);
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) > 10) {
            $phone = substr($phone, -10);
        }
        
        // Build address string
        $address = trim($shippingAddress['address_line_1']);
        if (!empty($shippingAddress['address_line_2'])) {
            $address .= ', ' . $shippingAddress['address_line_2'];
        }
        
        // Escape special characters that Delhivery doesn't accept
        $escapeString = function($str) {
            // Remove or replace special characters: &, #, %, ;, \
            $str = str_replace(['&', '#', '%', ';', '\\'], [' and ', ' ', ' ', ' ', ' '], $str);
            // Escape single quotes
            $str = str_replace("'", "\\'", $str);
            return $str;
        };
        
        // Create shipment data with all required fields
        $shipmentData = [
            // Consignee details
            'name' => substr($escapeString($shippingAddress['name']), 0, 35),
            'add' => substr($escapeString($address), 0, 80),
            'city' => $escapeString($shippingAddress['city']),
            'state' => $escapeString($shippingAddress['state']),
            'country' => 'India',
            'phone' => $phone,
            'pin' => (string) $shippingAddress['pincode'],
            
            // Order details
            'order' => $order->order_number,
            'payment_mode' => $order->payment_status === 'paid' ? 'Prepaid' : 'COD',
            'cod_amount' => $order->payment_status !== 'paid' ? (string) $order->total : '0',
            'total_amount' => (string) $order->total,
            
            // Product details
            'products_desc' => 'Spices and Food Products',
            'hsn_code' => '0910', // HSN code for spices
            'quantity' => (string) $order->items->sum('quantity'),
            'commodity_value' => (string) $order->subtotal,
            'weight' => (string) ($totalWeight / 1000), // Convert to kg
            
            // Seller details (required)
            'seller_name' => config('services.delhivery.seller_name', 'Flavearth'),
            'seller_add' => config('services.delhivery.return_address', 'Flavearth Store'),
            'seller_cst' => '',
            'seller_tin' => config('services.delhivery.gst_number', ''), // GST number if available
            'seller_gst_tin' => config('services.delhivery.gst_number', ''), // Required field
            'seller_inv' => $order->order_number,
            'seller_inv_date' => $order->created_at->format('Y/m/d'), // Try different date format
            
            // Shipment dimensions
            'shipment_width' => '20',
            'shipment_height' => '15',
            'shipment_length' => '25',
            
            // Optional fields that might be required
            'consignee_gst_amount' => '0',
            'seller_gst_amount' => '0',
            'taxable_amount' => '0',
            'cgst' => '0',
            'sgst' => '0',
            'igst' => '0',
            
            // Return address
            'return_name' => config('services.delhivery.seller_name', 'Flavearth'),
            'return_pin' => config('services.delhivery.return_pin', '110001'),
            'return_city' => config('services.delhivery.return_city', 'New Delhi'),
            'return_phone' => config('services.delhivery.return_phone', '9999999999'),
            'return_add' => config('services.delhivery.return_address', 'Flavearth Store'),
            'return_state' => config('services.delhivery.return_state', 'Delhi'),
            'return_country' => 'India',
            
            // Extra fields
            'fragile_shipment' => 'false',
            'waybill' => '' // Let Delhivery generate
            // Removed date fields: pickup_date, order_date, invoice_date
        ];
        
        return $shipmentData;
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        $url = $this->baseUrl . '/c/api/pin-codes/json/';
        
        try {
            // Try with Token auth
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
                'Accept' => 'application/json'
            ])->get($url, [
                'filter_codes' => '110001'
            ]);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'API connection successful',
                    'auth_method' => 'Token'
                ];
            }
            
            // Try with different auth header
            $response = Http::withHeaders([
                'Api-Token' => $this->apiKey,
                'Accept' => 'application/json'
            ])->get($url, [
                'filter_codes' => '110001'
            ]);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'API connection successful',
                    'auth_method' => 'Api-Token'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'API authentication failed',
                'status' => $response->status(),
                'response' => $response->body()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'API connection error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check serviceability for a pincode
     */
    public function checkServiceability($pincode)
    {
        $url = $this->baseUrl . '/c/api/pin-codes/json/';
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->apiKey,
            ])->get($url, [
                'filter_codes' => $pincode
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['delivery_codes'])) {
                    $pincodeData = $data['delivery_codes'][0];
                    return [
                        'success' => true,
                        'serviceable' => true,
                        'data' => $pincodeData
                    ];
                } else {
                    return [
                        'success' => true,
                        'serviceable' => false,
                        'message' => 'Delivery not available for this pincode'
                    ];
                }
            } else {
                Log::error('Delhivery API response error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [
                    'success' => false,
                    'error' => 'Failed to check serviceability - Status: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Delhivery serviceability check error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Serviceability check failed: ' . $e->getMessage()
            ];
        }
    }
}