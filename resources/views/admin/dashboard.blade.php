@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Overview of your e-commerce store performance')

@section('content')
<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-card-icon" style="background: linear-gradient(135deg, #059669, #047857);">
                <i class="fas fa-users text-white"></i>
            </div>
            <div class="stats-card-value">{{ number_format($totalUsers) }}</div>
            <div class="stats-card-label">Total Users</div>
            <div class="stats-card-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $activeUsers }} active users
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-card-icon" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                <i class="fas fa-boxes text-white"></i>
            </div>
            <div class="stats-card-value">{{ number_format($totalProducts) }}</div>
            <div class="stats-card-label">Total Products</div>
            <div class="stats-card-change {{ $activeProducts > 0 ? 'positive' : 'negative' }}">
                <i class="fas fa-{{ $activeProducts > 0 ? 'check' : 'exclamation-triangle' }}"></i>
                {{ $activeProducts }} in stock
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-card-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-shopping-bag text-white"></i>
            </div>
            <div class="stats-card-value">{{ number_format($totalOrders) }}</div>
            <div class="stats-card-label">Total Orders</div>
            <div class="stats-card-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $todayOrders }} today
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-card-icon" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                <i class="fas fa-rupee-sign text-white"></i>
            </div>
            <div class="stats-card-value">₹{{ number_format($totalRevenue, 0) }}</div>
            <div class="stats-card-label">Total Revenue</div>
            <div class="stats-card-change positive">
                <i class="fas fa-arrow-up"></i>
                ₹{{ number_format($todayRevenue, 0) }} today
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Orders Overview -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Orders & Revenue Overview</h5>
                    <p class="text-muted mb-0">Last 30 days performance</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-calendar me-1"></i> Last 30 Days
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 90 Days</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 350px;">
                    <canvas id="ordersRevenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Status Distribution -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="card-title">Order Status</h5>
                <p class="text-muted mb-0">Current distribution</p>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 300px;">
                    <canvas id="orderStatusChart"></canvas>
                </div>
                
                <!-- Status Legend -->
                <div class="mt-3">
                    <div class="row text-center">
                        <div class="col-6 mb-2">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="badge" style="background: #f59e0b; width: 10px; height: 10px; border-radius: 50%; margin-right: 8px;"></div>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="badge" style="background: #0ea5e9; width: 10px; height: 10px; border-radius: 50%; margin-right: 8px;"></div>
                                <small class="text-muted">Processing</small>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="badge" style="background: #dc2626; width: 10px; height: 10px; border-radius: 50%; margin-right: 8px;"></div>
                                <small class="text-muted">Shipped</small>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="badge" style="background: #059669; width: 10px; height: 10px; border-radius: 50%; margin-right: 8px;"></div>
                                <small class="text-muted">Delivered</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Row -->
<div class="row mb-4">
    <!-- Top Products -->
    <div class="col-xl-6 mb-4">
        <div class="modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Top Selling Products</h5>
                    <p class="text-muted mb-0">Best performers this month</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-right me-1"></i> View All
                </a>
            </div>
            <div class="card-body">
                @if($topProducts->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No sales data available yet.</p>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Products
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="border: none; background: none; font-weight: 600; color: #64748b;">Product</th>
                                    <th style="border: none; background: none; font-weight: 600; color: #64748b;">Sold</th>
                                    <th style="border: none; background: none; font-weight: 600; color: #64748b;">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                <tr>
                                    <td style="border: none; border-bottom: 1px solid #e2e8f0; padding: 1rem 0;">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                <i class="fas fa-box text-primary"></i>
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.products.show', $product->id) }}" 
                                                   class="text-decoration-none fw-medium">
                                                    {{ $product->name }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="border: none; border-bottom: 1px solid #e2e8f0; padding: 1rem 0;">
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            {{ $product->total_sold }}
                                        </span>
                                    </td>
                                    <td style="border: none; border-bottom: 1px solid #e2e8f0; padding: 1rem 0;">
                                        <strong>₹{{ number_format($product->price * $product->total_sold, 0) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-xl-6 mb-4">
        <div class="modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Recent Orders</h5>
                    <p class="text-muted mb-0">Latest customer orders</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-right me-1"></i> View All
                </a>
            </div>
            <div class="card-body">
                @if($recentOrders->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No orders yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="border: none; background: none; font-weight: 600; color: #64748b;">Customer</th>
                                    <th style="border: none; background: none; font-weight: 600; color: #64748b;">Amount</th>
                                    <th style="border: none; background: none; font-weight: 600; color: #64748b;">Status</th>
                                    <th style="border: none; background: none; font-weight: 600; color: #64748b;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td style="border: none; border-bottom: 1px solid #e2e8f0; padding: 1rem 0;">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-3" style="width: 36px; height: 36px; font-size: 0.875rem;">
                                                {{ substr($order->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $order->user->name }}</div>
                                                <small class="text-muted">#{{ $order->order_number }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="border: none; border-bottom: 1px solid #e2e8f0; padding: 1rem 0;">
                                        <strong>₹{{ number_format($order->total, 0) }}</strong>
                                    </td>
                                    <td style="border: none; border-bottom: 1px solid #e2e8f0; padding: 1rem 0;">
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'processing' => 'info', 
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $color = $statusColors[$order->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td style="border: none; border-bottom: 1px solid #e2e8f0; padding: 1rem 0;">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-header">
                <h5 class="card-title">Quick Actions</h5>
                <p class="text-muted mb-0">Common administrative tasks</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary w-100 py-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <span>Add Product</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.orders.index') }}?status=pending" class="btn btn-outline-warning w-100 py-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <span>Pending Orders</span>
                                @if($pendingOrders = App\Models\Order::where('status', 'pending')->count())
                                    <span class="badge bg-warning rounded-pill mt-1">{{ $pendingOrders }}</span>
                                @endif
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info w-100 py-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <span>Manage Users</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="#" class="btn btn-outline-secondary w-100 py-3">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-cog fa-2x mb-2"></i>
                                <span>Settings</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js configuration
Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.color = '#64748b';

// Orders & Revenue Combined Chart
const ordersRevenueCtx = document.getElementById('ordersRevenueChart').getContext('2d');
new Chart(ordersRevenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($ordersChartData['labels']) !!},
        datasets: [
            {
                label: 'Orders',
                data: {!! json_encode($ordersChartData['data']) !!},
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y'
            },
            {
                label: 'Revenue (₹)',
                data: {!! json_encode($revenueChartData['data']) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: '#e2e8f0',
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: true,
                callbacks: {
                    label: function(context) {
                        if (context.datasetIndex === 1) {
                            return 'Revenue: ₹' + context.parsed.y.toLocaleString();
                        }
                        return 'Orders: ' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                border: {
                    display: false
                }
            },
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                beginAtZero: true,
                grid: {
                    color: '#f1f5f9'
                },
                border: {
                    display: false
                },
                ticks: {
                    precision: 0
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                beginAtZero: true,
                grid: {
                    drawOnChartArea: false,
                },
                border: {
                    display: false
                },
                ticks: {
                    callback: function(value) {
                        return '₹' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Order Status Doughnut Chart
const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
new Chart(orderStatusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($orderStatusData['labels']) !!},
        datasets: [{
            data: {!! json_encode($orderStatusData['data']) !!},
            backgroundColor: [
                '#f59e0b', // pending - amber
                '#0ea5e9', // processing - sky blue  
                '#dc2626', // shipped - red (spice color)
                '#059669', // delivered - emerald green
                '#6b7280'  // cancelled - gray
            ],
            borderWidth: 0,
            cutout: '60%'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: '#e2e8f0',
                borderWidth: 1,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed * 100) / total).toFixed(1);
                        return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                    }
                }
            }
        },
        elements: {
            arc: {
                borderWidth: 0
            }
        }
    }
});

// Auto-refresh data every 5 minutes
setInterval(function() {
    // You can implement auto-refresh logic here
    console.log('Dashboard auto-refresh...');
}, 300000);
</script>
@endsection