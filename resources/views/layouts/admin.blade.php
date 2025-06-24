<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:300,400,600,700" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light" style="font-family: 'Nunito', sans-serif;">
    <div class="d-flex min-vh-100">
        <!-- Sidebar -->
        <nav class="bg-dark text-white position-fixed start-0 top-0 h-100 overflow-auto" style="width: 250px; z-index: 1000;">
            <!-- Sidebar Header -->
            <div class="p-4 border-bottom border-secondary" style="background-color: #1a252f;">
                <h4 class="text-primary m-0 fw-bold">
                    <a href="/" class="navbar-brand">
                        <i class="fas fa-book-open me-2"></i>LibrarySystem
                    </a>
                </h4>
                <small class="text-muted">Admin Panel</small>
            </div>

            <!-- Sidebar Menu -->
            <ul class="list-unstyled my-4">
                <li class="mb-1">
                    <a href="{{ route('admin.dashboard') }}"
                       class="d-flex align-items-center px-4 py-3 text-decoration-none text-light border-start border-3 {{ request()->routeIs('admin.dashboard') ? 'bg-secondary border-primary' : 'border-transparent' }} hover-bg-secondary">
                        <i class="fas fa-tachometer-alt me-3" style="width: 20px;"></i>
                        Dashboard
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.books.index') }}"
                       class="d-flex align-items-center px-4 py-3 text-decoration-none text-light border-start border-3 {{ request()->routeIs('admin.books.*') ? 'bg-secondary border-primary' : 'border-transparent' }} hover-bg-secondary">
                        <i class="fas fa-book me-3" style="width: 20px;"></i>
                        Books
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.categories.index') }}"
                       class="d-flex align-items-center px-4 py-3 text-decoration-none text-light border-start border-3 {{ request()->routeIs('admin.categories.*') ? 'bg-secondary border-primary' : 'border-transparent' }} hover-bg-secondary">
                        <i class="fas fa-tags me-3" style="width: 20px;"></i>
                        Categories
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.borrowings.index') }}"
                       class="d-flex align-items-center px-4 py-3 text-decoration-none text-light border-start border-3 {{ request()->routeIs('admin.borrowings.*') ? 'bg-secondary border-primary' : 'border-transparent' }} hover-bg-secondary">
                        <i class="fas fa-exchange-alt me-3" style="width: 20px;"></i>
                        Transactions
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.students.index') }}"
                       class="d-flex align-items-center px-4 py-3 text-decoration-none text-light border-start border-3
                              {{ request()->routeIs('admin.students.*') ? 'border-primary bg-secondary bg-opacity-25' : 'border-transparent' }}
                              hover-bg-secondary">
                        <i class="fas fa-users me-3" style="width: 20px;"></i>
                        Students
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.reports.index') }}"
                       class="d-flex align-items-center px-4 py-3 text-decoration-none text-light border-start border-3
                              {{ request()->routeIs('admin.reports.*') ? 'border-primary bg-secondary bg-opacity-25' : 'border-transparent' }}
                              hover-bg-secondary">
                        <i class="fas fa-chart-bar me-3" style="width: 20px;"></i>
                        Reports
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.settings.index') }}"
                       class="d-flex align-items-center px-4 py-3 text-decoration-none text-light border-start border-3
                              {{ request()->routeIs('admin.settings.*') ? 'border-primary bg-secondary bg-opacity-25' : 'border-transparent' }}
                              hover-bg-secondary">
                        <i class="fas fa-cog me-3" style="width: 20px;"></i>
                        Settings
                    </a>
                </li>
            </ul>

            <!-- Logout Section -->
            <div class="position-absolute bottom-0 start-0 end-0 p-4">
                <div class="border-top border-secondary pt-4">
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-danger text-decoration-none">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow-1" style="margin-left: 250px;">
            <!-- Top Bar -->
            <div class="bg-white shadow-sm border-bottom p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 text-dark fw-bold">@yield('page-title', 'Dashboard')</h5>
                    <small class="text-muted">{{ now()->format('l, F j, Y') }}</small>
                </div>

                <div class="d-flex align-items-center">
                    <div class="text-end me-3">
                        <div class="fw-bold text-dark">Library Management</div>
                        <small class="text-muted">Administrator</small>
                    </div>
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px;">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-4">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .hover-bg-secondary:hover {
            background-color: #6c757d !important;
        }
    </style>

    @stack('scripts')
</body>
</html>
