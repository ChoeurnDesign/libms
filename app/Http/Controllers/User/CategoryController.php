<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CategoryController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('categories')) {
            return view('user.categories.index', [
                'categories' => collect(),
                'stats' => ['total_categories' => 0, 'total_books' => 0]
            ]);
        }

        $categories = Category::withCount('books')->get();

        $stats = [
            'total_categories' => $categories->count(),
            'total_books' => Book::count(),
        ];

        return view('user.categories.index', compact('categories', 'stats'));
    }

    public function show(Category $category, Request $request)
    {
        $query = Book::where('category_id', $category->id);

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%")
                ->orWhere('author', 'like', "%{$request->search}%");
        }

        // Check if status column exists
        $bookColumns = Schema::getColumnListing('books');
        if (in_array('status', $bookColumns)) {
            $query->where('status', '!=', 'deleted');
        }

        $books = $query->orderBy('title')->paginate(12);

        $categoryStats = [
            'total_books' => $books->total(),
            'available_books' => 0,
        ];

        // Calculate available books if status column exists
        if (in_array('status', $bookColumns)) {
            $availableQuery = Book::where('category_id', $category->id)
                ->where('status', 'available');
            $categoryStats['available_books'] = $availableQuery->count();
        } else {
            $categoryStats['available_books'] = Book::where('category_id', $category->id)->count();
        }

        return view('user.categories.show', compact('category', 'books', 'categoryStats'));
    }
}
