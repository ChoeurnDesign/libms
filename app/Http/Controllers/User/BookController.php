<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Borrowing; // Make sure to import the Borrowing model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request)
    {
        // --- Logic for Search Suggestions (AJAX only) ---
        if ($request->ajax() && $request->input('ajax') === '1' && $request->has('query')) {
            $queryTerm = $request->input('query');

            if (empty($queryTerm) || strlen($queryTerm) < 2) {
                return response()->json([]);
            }

            $suggestions = Book::select('title', 'author')
                                ->where('title', 'LIKE', '%' . $queryTerm . '%')
                                ->orWhere('author', 'LIKE', '%' . $queryTerm . '%')
                                ->limit(10)
                                ->get();

            $formattedSuggestions = $suggestions->map(function ($book) {
                return [
                    'value' => $book->title,
                    'label' => $book->title . ' by ' . $book->author,
                ];
            });

            return response()->json($formattedSuggestions);
        }

        // --- Original Logic for Browse Books (Non-AJAX or AJAX with filters) ---
        $booksQuery = Book::with('category');

        // 1. Search by title or author
        if ($request->filled('search')) {
            // Assuming you have a `scopeSearch` method in your Book model
            $booksQuery->search($request->search);
        }

        // 2. Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            // Assuming you have a `scopeByCategory` method in your Book model
            $booksQuery->byCategory($request->category);
        }

        // 3. Filter by status (Crucial update here)
        if ($request->filled('status')) {
            if ($request->status === 'available') {
                // Show books with at least one available copy
                $booksQuery->where('available_quantity', '>', 0);
            } elseif ($request->status === 'borrowed') { // This else-if block handles 'borrowed' status
                // Show books that currently have at least one copy borrowed by ANY user
                // This requires a 'borrowings' relationship in your Book model
                $booksQuery->whereHas('borrowings', function ($query) {
                    $query->where('status', 'borrowed');
                });
            }
        }


        // 4. Sorting
        switch ($request->get('sort')) {
            case 'author':
                $booksQuery->orderBy('author');
                break;
            case 'newest':
                $booksQuery->orderBy('created_at', 'desc'); // Use created_at or a specific published_date
                break;
            case 'popular':
                if (Schema::hasColumn('books', 'borrow_count')) {
                    $booksQuery->orderByDesc('borrow_count');
                } else {
                    // Fallback if borrow_count doesn't exist, e.g., sort by total quantity or title
                    $booksQuery->orderBy('title'); // Default fallback
                }
                break;
            case 'title':
            default:
                $booksQuery->orderBy('title');
        }

        $books = $booksQuery->paginate(12);

        $categories = Category::all();

        return view('user.books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $book->load('category');

        $canBorrow = false;
        $borrowMessage = '';
        $hasActiveBorrowing = false;
        $userBorrowings = collect();
        $activeBorrowingsCount = 0;
        $borrowingLimit = 5;

        if (Auth::check()) {
            $user = Auth::user();

            if (Schema::hasTable('borrowings')) {
                $userBorrowings = DB::table('borrowings')
                    ->where('user_id', $user->id)
                    ->get();

                $activeBorrowingsCount = DB::table('borrowings')
                    ->where('user_id', $user->id)
                    ->where('status', 'borrowed')
                    ->count();

                $hasActiveBorrowing = DB::table('borrowings')
                    ->where('user_id', $user->id)
                    ->where('book_id', $book->id)
                    ->where('status', 'borrowed')
                    ->exists();
            }

            if ($book->available_quantity > 0) {
                if ($hasActiveBorrowing) {
                    $borrowMessage = 'You already have this book borrowed';
                } else {
                    if ($activeBorrowingsCount >= $borrowingLimit) {
                        $borrowMessage = 'You have reached the maximum borrowing limit (' . $borrowingLimit . ' books)';
                    } else {
                        $canBorrow = true;
                        $borrowMessage = 'Available for borrowing';
                    }
                }
            } else {
                $borrowMessage = 'Book is currently not available';
            }
        } else {
            $borrowMessage = 'Please login to borrow books';
        }

        return view('user.books.show', compact(
            'book',
            'canBorrow',
            'borrowMessage',
            'hasActiveBorrowing',
            'userBorrowings',
            'activeBorrowingsCount'
        ));
    }

    public function borrow(Request $request, Book $book)
    {
        $user = Auth::user();
        $borrowingLimit = 5;

        if (!Schema::hasTable('borrowings')) {
            return response()->json(['success' => false, 'message' => 'Borrowing system not available'], 500);
        }

        if ($book->available_quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'Book is not available for borrowing'], 400);
        }

        $existingBorrowing = DB::table('borrowings')
            ->where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', 'borrowed')
            ->first();

        if ($existingBorrowing) {
            return response()->json(['success' => false, 'message' => 'You already have this book borrowed'], 409);
        }

        $activeBorrowingsCount = DB::table('borrowings')
            ->where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->count();

        if ($activeBorrowingsCount >= $borrowingLimit) {
            return response()->json(['success' => false, 'message' => 'You have reached the maximum borrowing limit (' . $borrowingLimit . ' books)'], 403);
        }

        DB::table('borrowings')->insert([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(14)->format('Y-m-d'),
            'status' => 'borrowed',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $book->decrement('available_quantity');
        if (Schema::hasColumn('books', 'borrow_count')) {
            $book->increment('borrow_count');
        }

        return response()->json([
            'success' => true,
            'message' => 'Book borrowed successfully! Due date: ' . now()->addDays(14)->format('M d, Y')
        ]);
    }
}
