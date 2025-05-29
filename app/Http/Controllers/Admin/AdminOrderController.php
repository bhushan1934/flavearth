<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shipment;
use App\Services\DelhiveryService;
use App\Services\MockDelhiveryService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shipment']);
        
        // Search by order number or customer name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }
        
        $orders = $query->latest()->paginate(10);
        
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant', 'shipment']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        // Handle AJAX status update
        if ($request->ajax() || $request->wantsJson()) {
            $validated = $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            ]);
            
            $order->status = $validated['status'];
            $order->save();
            
            return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
        }
        
        // Handle full form update
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'tracking_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'shipping_address' => 'nullable|array',
            'shipping_address.name' => 'nullable|string',
            'shipping_address.phone' => 'nullable|string',
            'shipping_address.address_line_1' => 'nullable|string',
            'shipping_address.address_line_2' => 'nullable|string',
            'shipping_address.city' => 'nullable|string',
            'shipping_address.state' => 'nullable|string',
            'shipping_address.postal_code' => 'nullable|string',
            'shipping_address.country' => 'nullable|string',
        ]);

        // Update main order fields
        $order->status = $validated['status'];
        $order->payment_status = $validated['payment_status'];
        $order->tracking_number = $validated['tracking_number'] ?? null;
        $order->notes = $validated['notes'] ?? null;
        
        // Update shipping address if provided
        if (isset($validated['shipping_address'])) {
            $order->shipping_address = json_encode($validated['shipping_address']);
        }
        
        $order->save();

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Create shipment for an order
     */
    public function createShipment(Request $request, Order $order)
    {
        // Check if shipment already exists
        if ($order->shipment) {
            return response()->json([
                'success' => false,
                'message' => 'Shipment already created for this order'
            ], 400);
        }

        // Check if order is ready for shipment
        if (!in_array($order->status, ['processing', 'pending'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order is not ready for shipment'
            ], 400);
        }

        $delhiveryService = config('services.delhivery.use_mock') 
            ? new MockDelhiveryService() 
            : new DelhiveryService();
        
        // Create shipment via Delhivery API
        $result = $delhiveryService->createShipment(['order' => $order]);
        
        if ($result['success']) {
            // Extract waybill from response
            $responseData = $result['data'];
            $waybill = $responseData['packages'][0]['waybill'] ?? null;
            
            if ($waybill) {
                // Create shipment record
                $shipment = Shipment::create([
                    'order_id' => $order->id,
                    'waybill' => $waybill,
                    'courier_name' => 'delhivery',
                    'status' => 'pickup_scheduled',
                    'pickup_location' => config('services.delhivery.pickup_location'),
                    'shipment_data' => $responseData,
                    'shipped_at' => now()
                ]);

                // Update order status
                $order->update([
                    'status' => 'processing',
                    'tracking_number' => $waybill
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Shipment created successfully',
                    'waybill' => $waybill,
                    'shipment_id' => $shipment->id
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to create shipment',
            'error' => $result['error'] ?? 'Unknown error'
        ], 500);
    }

    /**
     * Cancel shipment
     */
    public function cancelShipment(Request $request, Order $order)
    {
        if (!$order->shipment) {
            return response()->json([
                'success' => false,
                'message' => 'No shipment found for this order'
            ], 404);
        }

        $delhiveryService = config('services.delhivery.use_mock') 
            ? new MockDelhiveryService() 
            : new DelhiveryService();
        $result = $delhiveryService->cancelShipment($order->shipment->waybill);
        
        if ($result['success']) {
            $order->shipment->update([
                'status' => 'cancelled',
                'notes' => 'Cancelled by admin'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shipment cancelled successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to cancel shipment'
        ], 500);
    }

    /**
     * Track shipment
     */
    public function trackShipment(Request $request, Order $order)
    {
        if (!$order->shipment) {
            return response()->json([
                'success' => false,
                'message' => 'No shipment found for this order'
            ], 404);
        }

        $delhiveryService = config('services.delhivery.use_mock') 
            ? new MockDelhiveryService() 
            : new DelhiveryService();
        $result = $delhiveryService->trackShipment($order->shipment->waybill);
        
        if ($result['success']) {
            $trackingData = $result['data'];
            
            // Update shipment tracking data
            $order->shipment->update([
                'tracking_data' => $trackingData,
                'status' => $this->mapDelhiveryStatus($trackingData['ShipmentData'][0]['Shipment']['Status']['Status'] ?? '')
            ]);

            return response()->json([
                'success' => true,
                'tracking_data' => $trackingData
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to track shipment'
        ], 500);
    }

    /**
     * Generate packing slip
     */
    public function generatePackingSlip(Request $request, Order $order)
    {
        if (!$order->shipment) {
            return response()->json([
                'success' => false,
                'message' => 'No shipment found for this order'
            ], 404);
        }

        $delhiveryService = config('services.delhivery.use_mock') 
            ? new MockDelhiveryService() 
            : new DelhiveryService();
        $result = $delhiveryService->generatePackingSlip($order->shipment->waybill);
        
        if ($result['success']) {
            // Return PDF response
            return response($result['pdf'])
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="packing-slip-' . $order->shipment->waybill . '.pdf"');
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate packing slip'
        ], 500);
    }

    /**
     * Map Delhivery status to our status
     */
    protected function mapDelhiveryStatus($delhiveryStatus)
    {
        $statusMap = [
            'Pending' => 'pending',
            'Manifested' => 'pickup_scheduled',
            'In Transit' => 'in_transit',
            'Dispatched' => 'out_for_delivery',
            'Delivered' => 'delivered',
            'RTO' => 'rto',
            'Cancelled' => 'cancelled'
        ];

        return $statusMap[$delhiveryStatus] ?? 'pending';
    }
}