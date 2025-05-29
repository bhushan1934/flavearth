<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Mock Delhivery Service for testing when the API is having issues
 */
class MockDelhiveryService extends DelhiveryService
{
    /**
     * Create a mock shipment for testing
     */
    public function createShipment($orderData)
    {
        Log::info('Using Mock Delhivery Service for testing');
        
        $order = $orderData['order'];
        $shippingAddress = is_string($order->shipping_address) 
            ? json_decode($order->shipping_address, true) 
            : $order->shipping_address;
        $mockWaybill = 'TEST' . str_pad(rand(100000, 999999), 10, '0', STR_PAD_LEFT);
        
        return [
            'success' => true,
            'data' => [
                'cash_pickups_count' => 0,
                'package_count' => 1,
                'packages' => [
                    [
                        'waybill' => $mockWaybill,
                        'name' => $shippingAddress['name'],
                        'order' => $order->order_number,
                        'payment' => $order->payment_status === 'paid' ? 'Prepaid' : 'COD',
                        'cod_amount' => $order->payment_status !== 'paid' ? $order->total : 0,
                        'status' => 'Success'
                    ]
                ],
                'success' => true,
                'prepaid_count' => $order->payment_status === 'paid' ? 1 : 0,
                'cod_count' => $order->payment_status !== 'paid' ? 1 : 0,
                'cod_amount' => $order->payment_status !== 'paid' ? $order->total : 0
            ]
        ];
    }
    
    /**
     * Track mock shipment
     */
    public function trackShipment($waybill)
    {
        return [
            'success' => true,
            'data' => [
                'ShipmentData' => [
                    [
                        'Shipment' => [
                            'Status' => [
                                'Status' => 'Manifested',
                                'StatusDateTime' => now()->format('Y-m-d H:i:s'),
                                'StatusType' => 'DL',
                                'Instructions' => 'Shipment is ready for pickup'
                            ],
                            'Waybill' => $waybill,
                            'ReferenceNo' => 'TEST-REF-' . rand(1000, 9999)
                        ]
                    ]
                ]
            ]
        ];
    }
    
    /**
     * Generate mock packing slip
     */
    public function generatePackingSlip($waybills)
    {
        // Generate a simple PDF-like response
        $content = "%PDF-1.4\nMock Packing Slip for " . implode(', ', (array) $waybills);
        
        return [
            'success' => true,
            'pdf' => $content
        ];
    }
    
    /**
     * Cancel mock shipment
     */
    public function cancelShipment($waybill)
    {
        return [
            'success' => true,
            'data' => [
                'status' => 'Cancelled',
                'waybill' => $waybill
            ]
        ];
    }
}