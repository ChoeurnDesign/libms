<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{

    /**
     * Display a listing of books
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Get books with optional category filter
        $query = Book::with('category')->latest();

        // Filter by category if provided
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('author', 'like', '%' . $request->search . '%')
                    ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }

        $books = $query->paginate(10);
        $categories = Category::all(); // For filter dropdown

        return view('admin.books.index', compact('books', 'categories'));
    }

    /**
     * Show the form for creating a new book
     */
    public function create()
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Get all categories for the dropdown
        $categories = Category::all();

        return view('admin.books.create', compact('categories'));
    }

    /**
     * Store a newly created book
     */
    public function store(Request $request)
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn',
            'category_id' => 'required|exists:categories,id',
            'total_copies' => 'required|integer|min:1',
            'available_copies' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'publisher' => 'nullable|string|max:255',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:10',
            'status' => 'nullable|in:available,unavailable,maintenance',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        $coverImagePath = null;

        // Handle image upload
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $coverImagePath = $image->storeAs('book_covers', $filename, 'public');
        }

        // Generate slug from title
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $counter = 1;

        // Ensure unique slug
        while (Book::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Create the book
        Book::create([
            'title' => $request->title,
            'slug' => $slug,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'cover_image' => $coverImagePath,
            'total_copies' => $request->total_copies,
            'available_copies' => $request->available_copies ?? $request->total_copies,
            'price' => $request->price,
            'published_year' => $request->published_year,
            'publisher' => $request->publisher,
            'pages' => $request->pages,
            'language' => $request->language ?? 'en',
            'status' => $request->status ?? 'available',
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Book added successfully!');
    }

    /**
     * Display the specified book
     */
    public function show(Book $book)
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        return view('admin.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book
     */
    public function edit(Book $book)
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        $categories = Category::all();

        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book
     */
    public function update(Request $request, Book $book)
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'category_id' => 'required|exists:categories,id',
            'total_copies' => 'required|integer|min:1',
            'available_copies' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'publisher' => 'nullable|string|max:255',
            'pages' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:10',
            'status' => 'nullable|in:available,unavailable,maintenance',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'total_copies' => $request->total_copies,
            'available_copies' => $request->available_copies ?? $request->total_copies,
            'price' => $request->price,
            'published_year' => $request->published_year,
            'publisher' => $request->publisher,
            'pages' => $request->pages,
            'language' => $request->language ?? 'en',
            'status' => $request->status ?? 'available',
        ];

        // Update slug if title changed
        if ($request->title !== $book->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $counter = 1;

            // Ensure unique slug (excluding current book)
            while (Book::where('slug', $slug)->where('id', '!=', $book->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $updateData['slug'] = $slug;
        }

        // Handle new image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $image = $request->file('cover_image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $updateData['cover_image'] = $image->storeAs('book_covers', $filename, 'public');
        }

        $book->update($updateData);

        return redirect()->route('admin.books.index')->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified book
     */
    public function destroy(Book $book)
    {
        // Check if user is admin
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }

        // Delete book cover image if exists
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully!');
    }

    /**
     * Filter books by category (AJAX endpoint)
     */
    public function filterByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');

        $query = Book::with('category');

        if ($categoryId && $categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        $books = $query->latest()->paginate(10);

        return response()->json([
            'books' => $books,
            'html' => view('admin.books.partials.book-list', compact('books'))->render()
        ]);
    }
}
