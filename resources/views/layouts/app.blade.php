<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Wing POS') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #0ea5e9;
            --background: #f8fafc;
            --surface: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --sidebar-bg: #0f172a;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            letter-spacing: -0.01em;
        }

        .sidebar {
            background-color: var(--sidebar-bg);
            color: white;
            min-height: 100vh;
            padding: 24px 0;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.6);
            padding: 12px 24px;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.05);
            border-left: 4px solid var(--primary);
            padding-left: 20px;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
        }

        .sidebar .brand {
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            color: white;
            margin-bottom: 20px;
        }

        .navbar-top {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            padding: 0;
            overflow-y: auto;
            max-height: calc(100vh - 80px);
        }

        .card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .stat-card {
            border-left: 4px solid var(--primary);
        }

        .stat-card h6 {
            color: #6c757d;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #1a1a1a;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .badge {
            font-weight: 500;
        }

        .alert {
            border: none;
            border-radius: 8px;
        }

        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 8px 16px;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex" style="height: 100vh;">
        <!-- Sidebar -->
        <nav class="sidebar" style="width: 250px; overflow-y: auto;">
            <div class="brand">
                <i class="bi bi-shop"></i> Wing POS
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}" 
                       href="{{ route('sales.create') }}">
                        <i class="bi bi-bag-check"></i> Point of Sale
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}" 
                       href="{{ route('sales.index') }}">
                        <i class="bi bi-receipt"></i> Sales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" 
                       href="{{ route('products.index') }}">
                        <i class="bi bi-box-seam"></i> Products
                    </a>
                </li>
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isManager() || auth()->user()->isOwner())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('expenses.*') || request()->routeIs('expense-categories.*') ? 'active' : '' }}" 
                       href="{{ route('expenses.index') }}">
                        <i class="bi bi-cash-stack"></i> Finance
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('promotions.*') ? 'active' : '' }}" 
                       href="{{ route('promotions.index') }}">
                        <i class="bi bi-ticket-perforated"></i> Promotions
                    </a>
                </li>
                @if(auth()->user()->isOwner() || auth()->user()->isSuperAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}" 
                       href="{{ route('audit-logs.index') }}">
                        <i class="bi bi-shield-lock"></i> Audit Trail
                    </a>
                </li>
                @endif
                @if(auth()->user()->isOwner())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('system.control') ? 'active' : '' }}" 
                       href="{{ route('system.control') }}">
                        <i class="bi bi-gear-wide-connected"></i> System Control
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('suppliers.*') || request()->routeIs('purchase-orders.*') ? 'active' : '' }}" 
                       href="{{ route('purchase-orders.index') }}">
                        <i class="bi bi-cart"></i> Purchases
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" 
                       href="{{ route('reports.sales') }}">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                       href="{{ route('users.index') }}">
                        <i class="bi bi-people"></i> Users
                    </a>
                </li>
                @endif
                <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link" style="border: none; background: none; width: 100%; text-align: left;">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div style="flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden;">
            <!-- Top Navbar -->
            <nav class="navbar-top">
                <div class="container-fluid px-4 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-info">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="main-content">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
                
                <!-- Footer -->
                <div class="px-4 py-3 mt-4 text-center border-top bg-white">
                    <small class="text-muted">Made by <strong>@cossi technologies</strong></small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
