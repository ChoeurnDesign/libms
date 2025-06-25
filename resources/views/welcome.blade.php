<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Library Management System') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-primary bg-gradient min-vh-100 d-flex flex-column" style="font-family: 'Nunito', sans-serif;">
    @include('layouts.navbar')

    <section class="py-5 text-center text-white flex-grow-1 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h1 class="display-1 fw-bold mb-4">
                        <i class="fas fa-book-open text-warning me-3"></i>
                        Welcome to My Library
                    </h1>
                    <p class="lead fs-2 mb-5 text-white-50">
                        Your digital gateway to knowledge and learning
                    </p>

                    @auth
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        {{-- Corrected route for authenticated users to browse books --}}
                        <a href="{{ route('user.books.index') }}"
                            class="btn btn-outline-light btn-lg px-5 py-3 fw-bold fs-5 rounded-pill">
                            <i class="fas fa-book me-2"></i>Browse Books
                        </a>
                    </div>
                    @else
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('login') }}"
                            class="btn btn-light btn-lg px-5 py-3 fw-bold fs-5 rounded-pill text-primary">
                            <i class="fas fa-rocket me-2"></i>Get Started
                        </a>
                    </div>
                    @endauth

                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-4 fw-bold text-primary mb-3">Library Features</h2>
                    <p class="lead text-muted">Discover what makes our library special</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-lg h-100 bg-white rounded-4">
                        <div class="card-body text-center p-5">
                            <div class="display-4 text-warning mb-4">
                                <i class="fas fa-book"></i>
                            </div>
                            <h4 class="fw-bold mb-3 text-primary">Vast Collection</h4>
                            <p class="text-muted mb-0">Access thousands of books across multiple categories and genres
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-lg h-100 bg-white rounded-4">
                        <div class="card-body text-center p-5">
                            <div class="display-4 text-success mb-4">
                                <i class="fas fa-search"></i>
                            </div>
                            <h4 class="fw-bold mb-3 text-primary">Easy Search</h4>
                            <p class="text-muted mb-0">Find your favorite books quickly with our advanced search system
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-lg h-100 bg-white rounded-4">
                        <div class="card-body text-center p-5">
                            <div class="display-4 text-info mb-4">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h4 class="fw-bold mb-3 text-primary">Digital Access</h4>
                            <p class="text-muted mb-0">Manage your library account anytime, anywhere with our online
                                system</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-primary bg-gradient">
        <div class="container">
            <div class="row text-center mb-4">
                <div class="col-12">
                    <h2 class="display-4 fw-bold text-white mb-3">Library Statistics</h2>
                    <p class="lead text-white-50">Our growing community and collection</p>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="card bg-transparent border-light border-2 rounded-4 p-4">
                        <div class="display-3 fw-bold text-warning mb-2">{{ App\Models\Book::count() ?? 0 }}</div>
                        <div class="fs-5 text-white">Books Available</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="card bg-transparent border-light border-2 rounded-4 p-4">
                        <div class="display-3 fw-bold text-warning mb-2">{{ App\Models\Category::count() ?? 0 }}</div>
                        <div class="fs-5 text-white">Categories</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="card bg-transparent border-light border-2 rounded-4 p-4">
                        <div class="display-3 fw-bold text-warning mb-2">
                            {{ App\Models\User::where('role', 'user')->count() ?? 0 }}</div>
                        <div class="fs-5 text-white">Active Members</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-transparent border-light border-2 rounded-4 p-4">
                        <div class="display-3 fw-bold text-warning mb-2">{{ App\Models\Borrowing::count() ?? 0 }}</div>
                        <div class="fs-5 text-white">Books Borrowed</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <div class="row text-center">
                <div class="col-12">
                    <p class="mb-2">
                        <i class="fas fa-book-open text-warning me-2"></i>
                        &copy; {{ date('Y') }} Library Management System
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
