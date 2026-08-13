<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>

    {{-- META --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- BOOTSTRAP CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- SELECT2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

    {{-- CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- VITE (TAILWIND + JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- GOOGLE FONTS --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- GLASSMORPHISM ADMIN STYLE --}}
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 0.5);
            --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        * {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: var(--bg-gradient);
            min-height: 100vh;
            background-attachment: fixed;
        }

        /* Glass Card */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: var(--glass-shadow);
        }

        /* Glass Sidebar */
        .glass-sidebar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-right: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.04);
            overflow-y: auto;
        }

        .glass-sidebar .sidebar-content {
            padding-bottom: 100px;
        }

        /* Glass Table */
        .glass-table {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            overflow: hidden;
        }

        .glass-table thead {
            background: rgba(102, 126, 234, 0.08);
        }

        .glass-table tbody tr {
            transition: all 0.2s;
        }

        .glass-table tbody tr:hover {
            background: rgba(102, 126, 234, 0.04);
        }

        /* Glass Form Controls */
        .glass-form-control {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
            border-radius: 12px !important;
            padding: 10px 16px !important;
        }

        .glass-form-control:focus {
            background: rgba(255, 255, 255, 0.95) !important;
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15) !important;
        }

        /* Glass Buttons */
        .btn-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-primary-glass {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 500;
            color: white;
            transition: all 0.2s;
        }

        .btn-primary-glass:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Alert Glass */
        .alert-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        /* Stat Card */
        .stat-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--glass-shadow);
            transition: all 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        /* Nav link active */
        .nav-link {
            border-radius: 10px;
            margin: 2px 0;
            transition: all 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea !important;
        }

        .nav-link.active {
            font-weight: 600;
        }

        /* Pagination */
        .pagination .page-link {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            margin: 0 2px;
            color: #495057;
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
        }

        /* Badge glass */
        .badge-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 4px 10px;
        }

        /* Chart container */
        .chart-container {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--glass-shadow);
        }

        /* Main content wrapper */
        .main-content {
            margin-left: 260px;
            padding: 24px;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }

        /* Flash message animation */
        .alert-glass {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Table responsive wrapper */
        .table-responsive {
            border-radius: 16px;
        }
    </style>

    {{-- PAGE LEVEL CSS --}}
    @stack('styles')
</head>

<body>
    <div class="d-flex">
        {{-- ✅ ADMIN SIDEBAR --}}
        <nav class="glass-sidebar position-fixed top-0 start-0 h-100 d-none d-md-block" style="width: 260px; z-index: 1000;">
            <div class="sidebar-content">
                <div class="p-4">
                    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none mb-4">
                        <div style="width: 36px; height: 36px; background: var(--primary-gradient); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px;">E</div>
                        <span style="font-weight: 700; font-size: 18px; color: #1a1a2e;">Ecommerce</span>
                    </a>

                    <div class="mt-4">
                    <p style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #8898aa; margin-bottom: 8px; padding-left: 12px;">Main</p>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" style="color: #495057;">
                        <span>📊</span> Dashboard
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.analytics') ? 'active' : '' }}" style="color: #495057;">
                        <span>📈</span> Analytics
                    </a>

                    <p style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #8898aa; margin: 16px 0 8px; padding-left: 12px;">Catalog</p>
                    <a href="{{ route('admin.products.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('products.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>📦</span> Products
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('categories.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>📁</span> Categories
                    </a>
                    <a href="{{ route('admin.colors.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('colors.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>🎨</span> Colors
                    </a>
                    <a href="{{ route('admin.sizes.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('sizes.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>📏</span> Sizes
                    </a>

                    <p style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #8898aa; margin: 16px 0 8px; padding-left: 12px;">Sales</p>
                    <a href="{{ route('admin.orders.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>🛒</span> Orders
                    </a>
                    <a href="{{ route('admin.cancellations.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.cancellations.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>↩️</span> Returns
                    </a>
                    <a href="{{ route('admin.coupons.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>🎫</span> Coupons
                    </a>

                    <p style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #8898aa; margin: 16px 0 8px; padding-left: 12px;">Marketing</p>
                    <a href="{{ route('admin.banners.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>🖼️</span> Banners
                    </a>
                    <a href="{{ route('admin.featured-products.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.featured-products.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>⭐</span> Featured
                    </a>
                    <a href="{{ route('admin.discounts.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('discounts.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>🏷️</span> Discounts
                    </a>

                    <p style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #8898aa; margin: 16px 0 8px; padding-left: 12px;">Management</p>
                    <a href="{{ route('admin.stock.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.stock.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>📊</span> Stock
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>👥</span> Customers
                    </a>
                    <a href="{{ route('admin.activity-logs.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>📋</span> Activity Logs
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>🔐</span> Roles
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>👔</span> Staff
                    </a>
                    <a href="{{ route('admin.cms-pages.index') }}" class="nav-link d-flex align-items-center gap-2 px-3 py-2 text-decoration-none {{ request()->routeIs('admin.cms-pages.*') ? 'active' : '' }}" style="color: #495057;">
                        <span>📄</span> CMS Pages
                    </a>
                </div>
            </div>
        </div>
    </nav>

        {{-- 📦 MAIN CONTENT --}}
        <main class="main-content flex-1">
            {{-- Top Bar --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <button class="btn btn-glass d-md-none me-2" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                        ☰
                    </button>
                    <h1 class="h4 mb-0 fw-bold" style="color: #1a1a2e;">@yield('title', 'Dashboard')</h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
                    <a href="{{ route('customer.products') }}" class="btn btn-glass btn-sm" target="_blank">View Store</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-glass btn-sm text-danger">Logout</button>
                    </form>
                </div>
            </div>

            {{-- 🔔 FLASH MESSAGES --}}
            @if(session('success'))
                <div class="alert alert-glass alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <span style="font-size: 20px; margin-right: 8px;">✅</span>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-glass alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <span style="font-size: 20px; margin-right: 8px;">❌</span>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- 📦 PAGE CONTENT --}}
            <div class="glass-card p-4">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Mobile Sidebar --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            {{-- Mobile nav items --}}
            <a href="{{ route('admin.dashboard') }}" class="nav-link d-block px-3 py-2 text-decoration-none">📊 Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="nav-link d-block px-3 py-2 text-decoration-none">📦 Products</a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link d-block px-3 py-2 text-decoration-none">🛒 Orders</a>
            <a href="{{ route('admin.customers.index') }}" class="nav-link d-block px-3 py-2 text-decoration-none">👥 Customers</a>
        </div>
    </div>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- SELECT2 GLOBAL INIT --}}
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select options',
                allowClear: true
            });
        });
    </script>

    {{-- ORDER VIEW TOGGLE (ADMIN ORDERS) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.view-order').forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.dataset.target;
                    const targetRow = document.getElementById(targetId);
                    if (!targetRow) return;
                    document.querySelectorAll('.order-details').forEach(row => {
                        if (row !== targetRow) row.classList.add('d-none');
                    });
                    targetRow.classList.toggle('d-none');
                });
            });
        });
    </script>

    {{-- PAGE LEVEL SCRIPTS --}}
    @stack('scripts')

</body>
</html>
