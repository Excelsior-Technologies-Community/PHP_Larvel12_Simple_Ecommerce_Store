<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Ecommerce Platform')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 0.5);
            --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bg-gradient: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%);
        }

        * {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            padding-top: 80px;
            background: var(--bg-gradient);
            background-attachment: fixed;
            min-height: 100vh;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: -1;
        }

        main {
            flex: 1;
        }

        /* Glassmorphism Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6) !important;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 22px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.4px;
        }

        .navbar .btn {
            border-radius: 12px;
            font-weight: 500;
            padding: 8px 18px;
            border: none;
            transition: all 0.2s;
        }

        .btn-glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 12px;
            color: #495057;
        }

        .btn-glass-nav:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-1px);
            color: #1a1a2e;
        }

        .btn-primary-nav {
            background: var(--primary-gradient);
            color: white;
            border-radius: 12px;
        }

        .btn-primary-nav:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Profile Image */
        .profile-img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.2s;
        }

        .profile-img:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .dropdown-menu {
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            padding: 8px;
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.15s;
        }

        .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.08);
        }

        /* Glass Cards */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: var(--glass-shadow);
            transition: all 0.2s;
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        /* Product Card */
        .product-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--glass-shadow);
            transition: all 0.3s;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }

        .product-card .product-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
        }

        .product-card .product-body {
            padding: 16px;
        }

        .product-card .product-title {
            font-weight: 700;
            font-size: 16px;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        .product-card .product-price {
            font-weight: 800;
            font-size: 20px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Badge Glass */
        .badge-glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            padding: 4px 10px;
        }

        /* Buttons */
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
            background: rgba(255, 255, 255, 0.95);
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

        /* Form Controls */
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

        /* Footer */
        footer {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-top: 1px solid rgba(255, 255, 255, 0.6);
            color: #475569;
            margin-top: auto;
        }

        footer h4, footer h5 {
            color: #1a1a2e;
            font-weight: 700;
        }

        footer a {
            color: #667eea;
            text-decoration: none;
            transition: 0.2s;
        }

        footer a:hover {
            color: #764ba2;
        }

        /* Alert Glass */
        .alert-glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }

        /* Star Rating */
        .star-rating {
            color: #ffc107;
            font-size: 18px;
        }

        .star-rating .star-empty {
            color: #dee2e6;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, #667eea, #764ba2);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 24px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #667eea;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timeline-item.completed::before {
            background: #28a745;
        }

        .timeline-item.active::before {
            background: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
        }

        /* Cart badge */
        .cart-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--primary-gradient);
            color: white;
            font-size: 10px;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Section title */
        .section-title {
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 2px;
        }

        /* Glass form label */
        .form-label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
            margin-bottom: 6px;
        }
    </style>
</head>

<body>

{{-- 🔹 NAVBAR --}}
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('customer.products') }}">
             Ecommerce Platform
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="ms-auto d-flex align-items-center gap-2 collapse navbar-collapse" id="navbarNav">
            @auth('customer')
                <a href="{{ route('customer.products') }}" class="btn btn-glass-nav btn-sm">
                    Products
                </a>

                <a href="{{ route('wishlist.index') }}" class="btn btn-glass-nav btn-sm">
                    💖 Wishlist
                </a>

                <a href="{{ route('compare.index') }}" class="btn btn-glass-nav btn-sm">
                    ⚖️ Compare
                </a>

                <a href="{{ route('cart.index') }}" class="btn btn-glass-nav btn-sm position-relative">
                    Cart
                    @php
                        $cartCount = \App\Models\Cart::where('customer_id', auth('customer')->id())->count();
                    @endphp
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>

                <a href="{{ route('customer.orders') }}" class="btn btn-glass-nav btn-sm">
                    Orders
                </a>

                {{-- PROFILE --}}
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" class="d-flex align-items-center">
                        <img
                            src="{{ auth('customer')->user()->profile_image
                                    ? asset('images/'.auth('customer')->user()->profile_image)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth('customer')->user()->name) . '&background=667eea&color=fff' }}"
                            class="profile-img"
                            alt="Profile">
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.profile') }}">
                                 My Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('customer.logout') }}">
                                 Logout
                            </a>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('customer.login') }}" class="btn btn-primary-nav btn-sm">
                    Login
                </a>
            @endauth

            <a href="{{ route('customer.products') }}" class="btn btn-glass-nav btn-sm">
                Shop Products
            </a>
        </div>
    </div>
</nav>

{{-- 🔹 PAGE CONTENT --}}
<main class="container my-4">
    @yield('content')
</main>

{{-- 🔹 FOOTER --}}
<footer class="pt-5 pb-3">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4">
                <h4> Ecommerce Platform</h4>
<div class="d-flex gap-3 mt-2 flex-wrap">
                     <a href="/about-us" class="footer-link">About Us</a>
                     <a href="/privacy-policy" class="footer-link">Privacy</a>
                     <a href="/terms-conditions" class="footer-link">Terms</a>
                 </div>
            </div>

            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled footer-links mt-2">
                    <li><a href="{{ route('customer.products') }}">Products</a></li>
                    <li><a href="{{ route('customer.orders') }}">My Orders</a></li>
                    <li><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li><a href="{{ route('customer.profile') }}">My Profile</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5>Support</h5>
                <p class="small mb-1"> support@ecommerceplatform.com</p>
                <p class="small mb-1"> +91 99999 88888</p>
                <p class="small mb-1"> Sindhu Bhavan Road, Ahmedabad – 395002</p>
            </div>
        </div>

        <hr class="my-4">

        <div class="text-center footer-bottom">
            © {{ date('Y') }} Ecommerce Platform. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- AUTO LOGOUT --}}
@auth('customer')
<script>
    window.addEventListener('unload', function () {
        navigator.sendBeacon("{{ route('customer.auto.logout') }}");
    });
</script>
@endauth

@stack('scripts')
</body>
</html>
