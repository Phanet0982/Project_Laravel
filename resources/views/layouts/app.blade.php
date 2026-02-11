<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sale Management System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --bg-primary: #0a0e1a;
            --bg-secondary: #111827;
            --bg-tertiary: #1f2937;
            --border: #374151;
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            --sidebar-width: 260px;
            --header-height: 64px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Header Styles */
        .navbar {
            height: var(--header-height);
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-primary) !important;
            letter-spacing: -0.02em;
        }

        .navbar-brand i {
            color: var(--primary);
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: var(--bg-secondary);
            border-right: 1px solid var(--border);
            overflow-y: auto;
            padding: 16px 12px;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 2px;
        }

        .sidebar .nav-link {
            color: var(--text-secondary);
            padding: 10px 12px;
            margin-bottom: 2px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link:hover {
            color: var(--text-primary);
            background: var(--bg-tertiary);
        }

        .sidebar .nav-link.active {
            color: var(--text-primary);
            background: var(--bg-tertiary);
        }

        .sidebar .nav-link i {
            font-size: 1rem;
            width: 18px;
        }

        /* Offcanvas Sidebar - Match Desktop Sidebar Style */
        .offcanvas {
            background-color: var(--bg-secondary);
            border-right: 1px solid var(--border);
        }

        .offcanvas-header {
            background-color: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            padding: 16px;
        }

        .offcanvas-title {
            color: var(--text-primary);
            font-weight: 600;
        }

        .offcanvas .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .offcanvas-body {
            background-color: var(--bg-secondary);
            padding: 16px 12px;
        }

        .offcanvas .nav-link {
            color: var(--text-secondary);
            padding: 10px 12px;
            margin-bottom: 2px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .offcanvas .nav-link:hover {
            color: var(--text-primary);
            background: var(--bg-tertiary);
        }

        .offcanvas .nav-link.active {
            color: var(--text-primary);
            background: var(--bg-tertiary);
        }

        .offcanvas .nav-link i {
            font-size: 1rem;
            width: 18px;
        }

        /* Main Content */
        main {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 24px;
            min-height: calc(100vh - var(--header-height));
        }

        /* Card Styles */
        .card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 12px;
            transition: border-color 0.15s ease;
        }

        .card:hover {
            border-color: var(--text-muted);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            font-weight: 600;
            font-size: 0.9375rem;
            color: var(--text-primary);
        }

        .card-body {
            padding: 20px;
            color: var(--text-primary);
        }

        /* Stat Cards */
        .stat-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            transition: all 0.15s ease;
        }

        .stat-card:hover {
            border-color: var(--text-muted);
        }

        .stat-card h3 {
            font-size: 1.875rem;
            font-weight: 600;
            margin-bottom: 4px;
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }

        .stat-card p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .stat-card small {
            font-size: 0.8125rem;
            color: var(--text-muted);
        }

        .stat-card i {
            opacity: 0.6;
        }

        /* Form Controls */
        .form-control, .form-select {
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }

        .form-control:focus, .form-select:focus {
            background: var(--bg-tertiary);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            color: var(--text-primary);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 6px;
        }

        /* Readonly Input Styling */
        .form-control:read-only,
        .form-control[readonly] {
            background: var(--bg-secondary);
            border-color: var(--border);
            color: var(--text-primary);
            cursor: default;
            font-weight: 600;
        }

        .form-control:read-only:focus,
        .form-control[readonly]:focus {
            background: var(--bg-secondary);
            border-color: var(--border);
            box-shadow: none;
        }

        /* Button Styles */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 16px;
            font-size: 0.875rem;
            transition: all 0.15s ease;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            color: white;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
        }

        .btn-info {
            background: var(--info);
            color: white;
        }

        .btn-info:hover {
            background: #0891b2;
            color: white;
        }

        .btn-outline-primary {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: rgba(99, 102, 241, 0.1);
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-outline-secondary {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }

        .btn-outline-secondary:hover {
            background: var(--bg-tertiary);
            border-color: var(--text-muted);
            color: var(--text-primary);
        }

        .btn-outline-success {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--success);
        }

        .btn-outline-success:hover {
            background: rgba(16, 185, 129, 0.1);
            border-color: var(--success);
            color: var(--success);
        }

        .btn-outline-info {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--info);
        }

        .btn-outline-info:hover {
            background: rgba(6, 182, 212, 0.1);
            border-color: var(--info);
            color: var(--info);
        }

        .btn-outline-warning {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--warning);
        }

        .btn-outline-warning:hover {
            background: rgba(245, 158, 11, 0.1);
            border-color: var(--warning);
            color: var(--warning);
        }

        .btn-outline-danger {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--danger);
        }

        .btn-outline-danger:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: var(--danger);
            color: var(--danger);
        }

        .btn-outline-light {
            border: 1px solid var(--border);
            color: var(--text-primary);
            background: transparent;
        }

        .btn-outline-light:hover {
            background: var(--bg-tertiary);
            border-color: var(--text-muted);
            color: var(--text-primary);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8125rem;
        }

        .btn-lg {
            padding: 12px 20px;
            font-size: 0.9375rem;
        }

        /* Table Styles */
        .table {
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .table th {
            background: transparent;
            border-bottom: 1px solid var(--border);
            font-weight: 500;
            color: var(--text-secondary);
            padding: 12px 16px;
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table td {
            border-bottom: 1px solid var(--border);
            padding: 14px 16px;
            vertical-align: middle;
            color: var(--text-secondary);
        }

        .table td .fw-medium,
        .table td .fw-bold {
            color: var(--text-primary);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background: rgba(255, 255, 255, 0.01);
        }

        /* Alert Styles */
        .alert {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.875rem;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }

        .alert-info {
            background: rgba(6, 182, 212, 0.1);
            border-color: rgba(6, 182, 212, 0.2);
            color: var(--info);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.2);
            color: var(--warning);
        }

        /* Modal Styles */
        .modal-content {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .modal-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 20px 24px;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.125rem;
            color: var(--text-primary);
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            background: transparent;
            border-top: 1px solid var(--border);
            padding: 16px 24px;
        }

        .btn-close {
            filter: invert(1);
            opacity: 0.5;
        }

        .btn-close:hover {
            opacity: 1;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.7);
        }

        /* Badge Styles */
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .bg-primary { background: var(--primary) !important; }
        .bg-success { background: var(--success) !important; }
        .bg-warning { background: var(--warning) !important; }
        .bg-danger { background: var(--danger) !important; }
        .bg-info { background: var(--info) !important; }
        .bg-secondary { background: var(--text-muted) !important; }

        /* Page Header */
        .page-header {
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
            letter-spacing: -0.02em;
        }

        .page-header p {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Input Groups */
        .input-group-text {
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-left: none;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .input-group .form-control {
            border-right: none;
        }

        /* Utilities */
        .text-muted {
            color: var(--text-secondary) !important;
        }

        .border-end {
            border-right: 1px solid var(--border) !important;
        }

        /* Icon Circles */
        .rounded-circle {
            border-radius: 50% !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Ensure all circular backgrounds are perfect circles */
        [class*="rounded-circle"],
        [class*="bg-primary"].rounded-circle,
        [class*="bg-success"].rounded-circle,
        [class*="bg-warning"].rounded-circle,
        [class*="bg-danger"].rounded-circle,
        [class*="bg-info"].rounded-circle,
        [class*="bg-secondary"].rounded-circle {
            aspect-ratio: 1 / 1;
            width: auto;
            height: auto;
        }

        /* Icon container sizing */
        .p-2 {
            padding: 8px !important;
        }

        .p-3 {
            padding: 12px !important;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }

        /* Responsive Design */
        @media (min-width: 992px) {
            .sidebar {
                width: var(--sidebar-width);
            }
            
            .main-content {
                margin-left: var(--sidebar-width);
                margin-top: var(--header-height);
                padding: 24px;
                min-height: calc(100vh - var(--header-height));
            }
        }
        
        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
                margin-top: var(--header-height);
                padding: 16px;
                min-height: calc(100vh - var(--header-height));
            }
        }

        /* Loading Animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5;  }
        }

        .loading {
            animation: pulse 2s infinite;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-cash-register"></i>
                Sale Management System
            </a>
            <div class="navbar-nav ms-auto d-flex flex-row align-items-center">
                <span class="navbar-text me-4 text-muted">
                    <i class="fas fa-user-circle me-2"></i>
                    Welcome, <strong>{{ Auth::user()->username }}</strong>
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Toggle Button -->
    <button class="btn btn-primary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas" style="position: fixed; top: 75px; left: 10px; z-index: 1050;">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar Offcanvas for Mobile -->
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarOffcanvas" data-bs-scroll="true" data-bs-backdrop="false">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body pt-4">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                        <i class="fas fa-cash-register"></i>
                        <span>Point of Sale</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('promotions.*') ? 'active' : '' }}" href="{{ route('promotions.index') }}">
                        <i class="fas fa-tags"></i>
                        <span>Promotions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                        <i class="fas fa-truck"></i>
                        <span>Suppliers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                        <i class="fas fa-user-tie"></i>
                        <span>Employees</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Fixed Sidebar for Desktop -->
    <nav class="sidebar d-none d-lg-block">
        <div class="position-sticky pt-4">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                        <i class="fas fa-cash-register"></i>
                        <span>Point of Sale</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('promotions.*') ? 'active' : '' }}" href="{{ route('promotions.index') }}">
                        <i class="fas fa-tags"></i>
                        <span>Promotions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                        <i class="fas fa-truck"></i>
                        <span>Suppliers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                        <i class="fas fa-user-tie"></i>
                        <span>Employees</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" href="{{ route('settings.index') }}">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast('{{ session('success') }}', 'success');
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showToast('{{ session('error') }}', 'error');
                });
            </script>
        @endif

        @yield('content')
    </main>

    <!-- Toast Container -->
    <div id="toastContainer" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 320px; max-width: 400px;"></div>

    <!-- Custom Confirm Modal -->
    <div class="modal fade" id="customConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                    <h5 id="confirmTitle" class="mb-2">Confirm Action</h5>
                    <p id="confirmMessage" class="text-muted mb-0">Are you sure you want to proceed?</p>
                </div>
                <div class="modal-footer border-0 justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmButton">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Toast Notification System
        function showToast(message, type = 'info', duration = 4000) {
            const container = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            
            // Set colors and icons based on type
            let bgColor, icon, iconBg;
            switch(type) {
                case 'success':
                    bgColor = '#10b981';
                    iconBg = 'rgba(16, 185, 129, 0.1)';
                    icon = 'fa-check-circle';
                    break;
                case 'error':
                case 'danger':
                    bgColor = '#ef4444';
                    iconBg = 'rgba(239, 68, 68, 0.1)';
                    icon = 'fa-times-circle';
                    break;
                case 'warning':
                    bgColor = '#f59e0b';
                    iconBg = 'rgba(245, 158, 11, 0.1)';
                    icon = 'fa-exclamation-triangle';
                    break;
                default:
                    bgColor = '#06b6d4';
                    iconBg = 'rgba(6, 182, 212, 0.1)';
                    icon = 'fa-info-circle';
            }
            
            // Create toast element
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.style.cssText = `
                background: var(--bg-secondary);
                border: 1px solid var(--border);
                border-left: 3px solid ${bgColor};
                border-radius: 8px;
                padding: 16px;
                margin-bottom: 12px;
                display: flex;
                align-items: center;
                gap: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                animation: slideIn 0.3s ease-out;
                position: relative;
                overflow: hidden;
            `;
            
            toast.innerHTML = `
                <div style="
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background: ${iconBg};
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                ">
                    <i class="fas ${icon}" style="color: ${bgColor}; font-size: 18px;"></i>
                </div>
                <div style="flex: 1; color: var(--text-primary); font-size: 14px; line-height: 1.5;">
                    ${message}
                </div>
                <button onclick="removeToast('${toastId}')" style="
                    background: none;
                    border: none;
                    color: var(--text-muted);
                    cursor: pointer;
                    padding: 4px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: color 0.15s;
                    flex-shrink: 0;
                " onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-muted)'">
                    <i class="fas fa-times" style="font-size: 14px;"></i>
                </button>
                <div style="
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    height: 3px;
                    background: ${bgColor};
                    animation: progress ${duration}ms linear;
                "></div>
            `;
            
            container.appendChild(toast);
            
            // Auto remove after duration
            setTimeout(() => {
                removeToast(toastId);
            }, duration);
        }
        
        function removeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }
        
        // Add animations to head
        if (!document.getElementById('toast-animations')) {
            const style = document.createElement('style');
            style.id = 'toast-animations';
            style.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(400px);
                        opacity: 0;
                    }
                }
                
                @keyframes progress {
                    from {
                        width: 100%;
                    }
                    to {
                        width: 0%;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        // Custom Confirm Function
        function showConfirm(message, onConfirm, title = 'Confirm Action') {
            const modal = new bootstrap.Modal(document.getElementById('customConfirmModal'));
            const titleEl = document.getElementById('confirmTitle');
            const messageEl = document.getElementById('confirmMessage');
            const confirmBtn = document.getElementById('confirmButton');
            
            titleEl.textContent = title;
            messageEl.textContent = message;
            
            // Remove old event listeners by cloning
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
            
            // Add new event listener
            newConfirmBtn.addEventListener('click', function() {
                modal.hide();
                if (typeof onConfirm === 'function') {
                    onConfirm();
                }
            });
            
            modal.show();
        }

        // Override default alert to use toast
        window.alert = function(message) {
            showToast(message, 'info');
        };
        
        // Alias for backward compatibility
        window.showAlert = showToast;
    </script>
    
    @stack('scripts')
</body>
</html>