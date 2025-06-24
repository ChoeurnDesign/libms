@extends('layouts.app')

@section('title', 'Browse Books')

@section('content')
@include('layouts.navbar')

<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light py-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="position-relative">
                        <input type="text" id="searchBooks" class="form-control rounded-pill ps-5"
                            placeholder="Search books..." value="{{ request('search') }}">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        {{-- Autocomplete Suggestions Container --}}
                        <div id="autocomplete-results" class="list-group position-absolute w-100 mt-1 shadow-sm" style="z-index: 1000; display: none;">
                            {{-- Suggestions will be injected here --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select rounded-pill" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select rounded-pill" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select rounded-pill" id="sortFilter">
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>By Title</option>
                        <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>By Author</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary rounded-pill w-100" onclick="clearFilters()">
                        <i class="fas fa-undo me-1"></i>Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if($books->count() > 0)
    <div class="row">
        @foreach($books as $book)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm book-card">
                    <div class="position-relative">
                        {{-- Favorite button: Now always defaults to outlined heart --}}
                        <button type="button" class="btn btn-link p-0 border-0 favorite-toggle-btn position-absolute top-0 end-0 m-2"
                                data-book-id="{{ $book->id }}"
                                style="font-size: 1.5rem; z-index:2;"
                                title="Add to Favorites">
                            <i class="far fa-heart text-muted"></i>
                        </button>

                        @if($book->cover_url)
                            <img src="{{ $book->cover_url }}" class="card-img-top"
                                style="width: 100%; object-fit: contain; height: 250px;" alt="{{ $book->title }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                style="height: 250px;">
                                <i class="fas fa-book fa-4x text-muted"></i>
                            </div>
                        @endif
                        @if($book->is_available)
                            <span class="badge bg-success position-absolute top-0 start-0 m-2">Available</span>
                        @else
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">Borrowed</span>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ Str::limit($book->title, 40) }}</h6>
                        <p class="text-muted small mb-1">by {{ $book->author }}</p>
                        @if($book->category)
                            <span class="badge bg-primary mb-2 d-inline-block w-auto align-self-start">{{ $book->category->name }}</span>
                        @endif
                        @if($book->description)
                            <p class="card-text small text-muted">{{ Str::limit($book->description, 80) }}</p>
                        @endif
                        <p class="text-muted small mb-2">
                            <i class="fas fa-copy me-1"></i>{{ $book->available_quantity }} of {{ $book->quantity }} available
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('user.books.show', $book->id) }}"
                               class="btn btn-primary btn-sm w-100 mb-2 rounded-pill">
                                View Details
                            </a>
                            @if($book->is_available)
                                <button class="btn btn-success btn-sm w-100 rounded-pill borrow-book-btn"
                                        data-id="{{ $book->id }}">
                                    Borrow Book
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm w-100 rounded-pill" disabled>
                                    Not Available
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center mb-2 mt-4 text-muted">
        <small>
            Showing {{ $books->firstItem() }} to {{ $books->lastItem() }} of {{ $books->total() }} results
        </small>
    </div>
    <div class="d-flex justify-content-center">
        {{ $books->withQueryString()->links('pagination::bootstrap-4', ['size' => 'sm']) }}
    </div>
    @else
    <div class="text-center py-5">
        <i class="fas fa-book fa-4x text-muted mb-3"></i>
        <h4>No Books Found</h4>
        <button class="btn btn-primary rounded-pill" onclick="clearFilters()">Show All Books</button>
    </div>
    @endif
</div>

<style>
    .book-card {
        transition: transform 0.2s ease;
        border: none;
    }
    .book-card:hover {
        transform: translateY(-5px);
    }
    /* Style for autocomplete suggestions */
    #autocomplete-results {
        max-height: 200px;
        overflow-y: auto;
    }
    #autocomplete-results .list-group-item {
        cursor: pointer;
        border-radius: 0;
    }
    #autocomplete-results .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded. Script is running.');

        const searchInput = document.getElementById('searchBooks');
        const autocompleteResults = document.getElementById('autocomplete-results');
        let debounceTimeout;

        // --- Autocomplete Logic ---
        searchInput.addEventListener('input', function() {
            console.log('Search input changed:', this.value);
            clearTimeout(debounceTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                autocompleteResults.style.display = 'none';
                return;
            }

            debounceTimeout = setTimeout(() => {
                const url = new URL('{{ route("user.books.index") }}'); // Corrected route name
                url.searchParams.set('query', query);
                url.searchParams.set('ajax', '1');

                console.log('Fetching suggestions from:', url.toString());

                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Suggestion fetch response status:', response.status);
                    if (!response.ok) {
                        console.error('Network response was not ok for suggestions:', response.statusText);
                        autocompleteResults.style.display = 'none';
                        throw new Error('Network response was not ok.');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Suggestions received:', data);
                    autocompleteResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('a');
                            div.classList.add('list-group-item', 'list-group-item-action', 'py-2');
                            div.textContent = item.label;
                            div.setAttribute('data-value', item.value);
                            div.addEventListener('click', function() {
                                searchInput.value = this.getAttribute('data-value');
                                autocompleteResults.style.display = 'none';
                                filterBooks(); // Trigger full search
                            });
                            autocompleteResults.appendChild(div);
                        });
                        autocompleteResults.style.display = 'block';
                    } else {
                        autocompleteResults.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error fetching search suggestions:', error);
                    autocompleteResults.style.display = 'none';
                });
            }, 300);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !autocompleteResults.contains(event.target)) {
                autocompleteResults.style.display = 'none';
            }
        });

        // --- Filter/Sort/Search (Existing Logic) ---
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                console.log('Enter key pressed in search input.');
                filterBooks();
                autocompleteResults.style.display = 'none';
            }
        });

        // Debugging for dropdowns - Using individual checks for robustness
        const categoryFilter = document.getElementById('categoryFilter');
        const statusFilter = document.getElementById('statusFilter');
        const sortFilter = document.getElementById('sortFilter');

        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                console.log('Category filter changed to:', this.value);
                filterBooks();
            });
        } else {
            console.error('Element with ID "categoryFilter" not found!');
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                console.log('Status filter changed to:', this.value);
                filterBooks();
            });
        } else {
            console.error('Element with ID "statusFilter" not found!');
        }

        if (sortFilter) {
            sortFilter.addEventListener('change', function() {
                console.log('Sort filter changed to:', this.value);
                filterBooks();
            });
        } else {
            console.error('Element with ID "sortFilter" not found!');
        }


        // --- AJAX Borrow book ---
        document.querySelectorAll('.borrow-book-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var bookId = btn.getAttribute('data-id');
                if (window.confirm('Do you want to borrow this book?')) {
                    // Corrected route helper usage for borrowing
                    fetch("{{ route('user.books.borrow', ['book' => ':bookId']) }}".replace(':bookId', bookId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 401) {
                                alert('You must be logged in to borrow books.');
                            } else {
                                alert('An error occurred. Please try again.');
                            }
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message);
                        if (data.success) window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Borrow failed. Please try again.');
                    });
                }
            });
        });

        // --- AJAX Favorite toggle ---
        document.querySelectorAll('.favorite-toggle-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const bookId = btn.getAttribute('data-book-id');

                // Corrected route helper usage for favorite toggle
                fetch("{{ route('user.favorites.toggle', ['book' => ':bookId']) }}".replace(':bookId', bookId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 401) {
                               alert('You must be logged in to add to favorites.');
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                        throw new Error('Network response was not ok.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const icon = btn.querySelector('i');
                        if (data.is_favorited) {
                            icon.classList.remove('far', 'text-muted');
                            icon.classList.add('fas', 'text-danger');
                            btn.setAttribute('title', 'Remove from Favorites');
                        } else {
                            icon.classList.remove('fas', 'text-danger');
                            icon.classList.add('far', 'text-muted');
                            btn.setAttribute('title', 'Add to Favorites');
                        }
                    } else {
                        alert(data.message || 'Failed to update favorite status.');
                    }
                })
                .catch(error => {
                    console.error('Error toggling favorite:', error);
                    alert('An error occurred while updating favorites.');
                });
            });
        });
    });

    // --- Global Functions ---
    function filterBooks() {
        console.log('filterBooks function called.');
        const search = document.getElementById('searchBooks').value;
        const category = document.getElementById('categoryFilter').value;
        const status = document.getElementById('statusFilter').value;
        const sort = document.getElementById('sortFilter').value;

        console.log('Current filter values:', { search, category, status, sort });

        const url = new URL(window.location.origin + window.location.pathname);

        // Clear all relevant query parameters
        ['search', 'category', 'status', 'sort', 'page', 'query', 'ajax'].forEach(param => url.searchParams.delete(param));

        // Add parameters only if they have values
        if (search) url.searchParams.set('search', search);
        if (category) url.searchParams.set('category', category);
        if (status) url.searchParams.set('status', status);
        if (sort) url.searchParams.set('sort', sort);

        console.log('Redirecting to URL:', url.toString());
        window.location.href = url.toString();
    }

    function clearFilters() {
        console.log('Clear filters called. Redirecting to base URL.');
        window.location.href = '{{ route("user.books.index") }}'; // Corrected route name
    }
</script>
@endsection
