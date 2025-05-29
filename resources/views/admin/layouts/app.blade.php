<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Flavearth Admin</title>
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Store-aligned color palette */
            --primary-color: #059669;
            --primary-dark: #047857;
            --primary-light: #10b981;
            --secondary-color: #dc2626;
            --secondary-light: #ef4444;
            --accent-color: #f59e0b;
            --accent-light: #fbbf24;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #dc2626;
            --info-color: #0ea5e9;
            --dark-color: #1f2937;
            --medium-dark: #374151;
            --light-gray: #f8fafc;
            --medium-gray: #6b7280;
            --border-color: #e5e7eb;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --header-height: 70px;
            
            /* Professional gradients */
            --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            --gradient-secondary: linear-gradient(135deg, var(--secondary-color) 0%, var(--secondary-light) 100%);
            --gradient-accent: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--light-gray);
            font-size: 14px;
            line-height: 1.6;
            color: var(--dark-color);
        }

        /* Header Styles */
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: var(--gradient-primary);
            box-shadow: 0 4px 20px rgba(5, 150, 105, 0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
        }

        .admin-header .logo {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-header .logo i {
            font-size: 1.75rem;
            color: #fef3c7;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-btn:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateY(-1px);
        }

        .user-dropdown {
            position: relative;
        }

        .user-dropdown .dropdown-toggle {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-dropdown .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--gradient-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
        }

        /* Enhanced Sidebar Styles */
        .admin-sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%);
            box-shadow: 4px 0 24px rgba(0,0,0,0.06);
            overflow-y: auto;
            overflow-x: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999;
            border-right: 1px solid var(--border-color);
        }

        .sidebar-content {
            padding: 2rem 0;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0 1.5rem;
            margin: 0;
        }

        .sidebar-nav-item {
            margin-bottom: 0.5rem;
        }

        /* Minimalistic Navigation Links */
        .sidebar-nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            color: var(--medium-gray);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .sidebar-nav-link:hover {
            color: var(--primary-color);
            background: rgba(5, 150, 105, 0.05);
        }

        .sidebar-nav-link.active {
            color: var(--primary-color);
            background: rgba(5, 150, 105, 0.1);
            font-weight: 600;
        }

        .sidebar-nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Simple Badge Design */
        .sidebar-nav-badge {
            min-width: 18px;
            height: 18px;
            background: var(--danger-color);
            color: white;
            font-size: 0.6875rem;
            font-weight: 600;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 0.25rem;
        }

        /* Mobile Collapsed Sidebar */
        .admin-sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .admin-sidebar.collapsed .sidebar-nav-link span {
            display: none;
        }

        .admin-sidebar.collapsed .sidebar-nav-link {
            justify-content: center;
            padding: 1rem 0.5rem;
        }

        /* Scrollbar Styling */
        .admin-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 2px;
            opacity: 0.3;
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            min-height: calc(100vh - var(--header-height));
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* Content Area */
        .content-header {
            margin-bottom: 2rem;
        }

        .content-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .content-subtitle {
            color: var(--medium-gray);
            font-size: 1rem;
        }

        .breadcrumb-nav {
            background: none;
            padding: 0;
            margin-bottom: 1rem;
        }

        .breadcrumb-nav .breadcrumb-item {
            color: var(--medium-gray);
        }

        .breadcrumb-nav .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 500;
        }

        /* Cards */
        .modern-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .modern-card .card-header {
            background: none;
            border-bottom: 1px solid var(--border-color);
            padding: 1.5rem 2rem;
            border-radius: 16px 16px 0 0;
        }

        .modern-card .card-body {
            padding: 2rem;
        }

        .modern-card .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .stats-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stats-card-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .stats-card-label {
            color: var(--medium-gray);
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .stats-card-change {
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stats-card-change.positive {
            color: var(--success-color);
        }

        .stats-card-change.negative {
            color: var(--danger-color);
        }

        /* Buttons */
        .btn-modern {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }

        /* Tables */
        .modern-table {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
        }

        .modern-table .table {
            margin-bottom: 0;
        }

        .modern-table .table th {
            background: var(--light-gray);
            color: var(--dark-color);
            font-weight: 600;
            border: none;
            padding: 1rem 1.5rem;
        }

        .modern-table .table td {
            padding: 1rem 1.5rem;
            border: none;
            border-bottom: 1px solid var(--border-color);
        }

        .modern-table .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Alerts */
        .alert-modern {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            font-weight: 500;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
                padding: 1rem;
            }

            .admin-header {
                padding: 0 1rem;
            }

            .content-title {
                font-size: 1.5rem;
            }

            .stats-card {
                padding: 1.5rem;
            }
        }

        /* Sidebar Toggle */
        .sidebar-toggle {
            display: none;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Notification Toast */
        .toast-container {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 9999;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <div class="d-flex align-items-center">
            <button class="sidebar-toggle me-3" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <i class="fas fa-leaf"></i>
                <span>Flavearth Admin</span>
            </a>
        </div>
        
        <div class="header-actions">
            <a href="{{ route('home') }}" target="_blank" class="header-btn">
                <i class="fas fa-external-link-alt"></i>
                <span class="d-none d-md-inline">View Store</span>
            </a>
            
            <div class="dropdown user-dropdown">
                <button class="dropdown-toggle header-btn" type="button" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header">{{ Auth::user()->name }}</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cog me-2"></i> Settings
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-content">
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('admin.products.index') }}" 
                       class="sidebar-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="fas fa-boxes"></i>
                        <span>Products</span>
                        @php
                            $lowStockCount = App\Models\Product::where('stock_quantity', '<=', 10)->count();
                        @endphp
                        @if($lowStockCount > 0)
                            <span class="sidebar-nav-badge">{{ $lowStockCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('admin.orders.index') }}" 
                       class="sidebar-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Orders</span>
                        @php
                            $pendingOrders = App\Models\Order::where('status', 'pending')->count();
                        @endphp
                        @if($pendingOrders > 0)
                            <span class="sidebar-nav-badge">{{ $pendingOrders }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="{{ route('admin.users.index') }}" 
                       class="sidebar-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Breadcrumb -->
        @if(!request()->routeIs('admin.dashboard'))
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                @yield('breadcrumb')
            </ol>
        </nav>
        @endif

        <!-- Page Content -->
        <div class="content-header">
            <h1 class="content-title">@yield('title', 'Dashboard')</h1>
            @hasSection('subtitle')
                <p class="content-subtitle">@yield('subtitle')</p>
            @endif
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-modern alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('adminSidebar');
            sidebar.classList.toggle('show');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Global notification function
        window.showNotification = function(message, type = 'info', duration = 5000) {
            const container = document.querySelector('.toast-container');
            const toastId = 'toast-' + Date.now();
            
            const toastHtml = `
                <div class="toast align-items-center text-bg-${type} border-0" id="${toastId}" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', toastHtml);
            const toast = new bootstrap.Toast(document.getElementById(toastId), { delay: duration });
            toast.show();
            
            // Remove toast element after it's hidden
            document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
                this.remove();
            });
        };
    </script>
    @yield('scripts')
</body>
</html>