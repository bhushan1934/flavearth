<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic Statistics
        $totalUsers = User::where('is_admin', false)->count();
        $activeUsers = User::where('is_admin', false)->where('is_active', true)->count();
        $totalProducts = Product::count();
        $activeProducts = Product::where('in_stock', true)->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total');
        
        // Today's Statistics
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $todayRevenue = Order::whereDate('created_at', Carbon::today())->sum('total');
        $todayUsers = User::whereDate('created_at', Carbon::today())->count();
        
        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // Orders chart data (last 30 days)
        $ordersChartData = $this->getOrdersChartData(30);
        
        // Revenue chart data (last 30 days)
        $revenueChartData = $this->getRevenueChartData(30);
        
        // User registration chart data (last 30 days)
        $usersChartData = $this->getUsersChartData(30);
        
        // Top selling products
        $topProducts = $this->getTopSellingProducts(5);
        
        // Sales by category
        $categoryData = $this->getSalesByCategory();
        
        // Order status distribution
        $orderStatusData = $this->getOrderStatusDistribution();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'totalProducts',
            'activeProducts',
            'totalOrders',
            'totalRevenue',
            'todayOrders',
            'todayRevenue',
            'todayUsers',
            'recentOrders',
            'ordersChartData',
            'revenueChartData',
            'usersChartData',
            'topProducts',
            'categoryData',
            'orderStatusData'
        ));
    }
    
    private function getOrdersChartData($days)
    {
        $data = ['labels' => [], 'data' => []];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $data['labels'][] = $date->format('M d');
            $data['data'][] = Order::whereDate('created_at', $date)->count();
        }
        
        return $data;
    }
    
    private function getRevenueChartData($days)
    {
        $data = ['labels' => [], 'data' => []];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $data['labels'][] = $date->format('M d');
            $data['data'][] = Order::whereDate('created_at', $date)->sum('total');
        }
        
        return $data;
    }
    
    private function getUsersChartData($days)
    {
        $data = ['labels' => [], 'data' => []];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $data['labels'][] = $date->format('M d');
            $data['data'][] = User::whereDate('created_at', $date)
                ->where('is_admin', false)
                ->count();
        }
        
        return $data;
    }
    
    private function getTopSellingProducts($limit = 5)
    {
        return Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }
    
    private function getSalesByCategory()
    {
        $categories = DB::table('categories')
            ->select('categories.name', DB::raw('COUNT(DISTINCT orders.id) as order_count'), DB::raw('SUM(order_items.price * order_items.quantity) as revenue'))
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();
            
        return [
            'labels' => $categories->pluck('name')->toArray(),
            'data' => $categories->pluck('revenue')->toArray()
        ];
    }
    
    private function getOrderStatusDistribution()
    {
        $statuses = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
            
        $statusLabels = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
        ];
        
        return [
            'labels' => $statuses->map(fn($s) => $statusLabels[$s->status] ?? ucfirst($s->status))->toArray(),
            'data' => $statuses->pluck('count')->toArray()
        ];
    }
}