<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4 text-warning" href="{{ url('') }}">
            <i class="fas fa-book-reader me-2"></i>My Library
        </a>
        <div class="navbar-nav ms-auto">
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
