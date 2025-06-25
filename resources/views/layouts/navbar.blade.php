<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4 text-warning" href="{{ url('') }}">
            <i class="fas fa-book-reader me-2"></i>My Library
        </a>

        <!-- Hamburger Toggle for Mobile -->
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Offcanvas Menu for Mobile -->
        <div class="offcanvas offcanvas-end bg-dark text-white d-lg-none" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title text-warning" id="offcanvasNavbarLabel">My Library</h5>
                <button type="button" class="btn-close btn-close-white text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-semibold text-white" href="{{ route('user.books.index') }}">
                                <i class="fas fa-book me-2"></i>Browse Books
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-semibold text-white" href="{{ route('user.borrowings.index') }}">
                                <i class="fas fa-book-reader me-2"></i>My Books
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-semibold text-white" href="{{ route('home') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-semibold text-white" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form-offcanvas').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                            <form id="logout-form-offcanvas" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-semibold text-white" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-semibold text-white" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>

        <!-- Desktop Nav -->
        <div class="navbar-nav ms-auto d-none d-lg-flex">
            @auth
                <a class="nav-link px-3 fw-semibold text-white" href="{{ route('user.books.index') }}">
                    <i class="fas fa-book me-2"></i>Browse Books
                </a>
                <a class="nav-link px-3 fw-semibold text-white" href="{{ route('user.borrowings.index') }}">
                    <i class="fas fa-book-reader me-2"></i>My Books
                </a>
                <a class="nav-link px-3 fw-semibold text-white" href="{{ route('home') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a class="nav-link px-3 fw-semibold text-white" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @else
                <a class="nav-link px-3 fw-semibold text-white" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
                <a class="nav-link px-3 fw-semibold text-white" href="{{ route('register') }}">
                    <i class="fas fa-user-plus me-2"></i>Register
                </a>
            @endauth
        </div>
    </div>
</nav>

<!-- Bootstrap JS Bundle (needed for offcanvas) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
