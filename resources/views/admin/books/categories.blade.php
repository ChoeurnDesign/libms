@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tags text-success"></i> Book Categories</h2>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add Category
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @if(isset($categories) && $categories->count() > 0)
                <div class="row">
                    @foreach($categories as $category)
                    <div class="col-md-4 mb-3">
                        <div class="card border">
                            <div class="card-body">
                                <h5 class="card-title">{{ $category->name }}</h5>
                                <p class="text-muted">{{ $category->description ?? 'No description' }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-info">{{ $category->books_count ?? 0 }} books</span>
                                    <div>
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete category?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                    <h5>No categories found</h5>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-success">Create First Category</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
