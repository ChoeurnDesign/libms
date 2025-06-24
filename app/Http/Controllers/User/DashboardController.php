<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'borrowed_books' => 0,
            'due_soon' => 0,
            'overdue' => 0,
            'favorites' => 0,
            'total_read' => 0,
            'fine_amount' => 0,
        ];

        $recentBorrowings = collect();
        $dueSoonBooks = collect();
        $overdueBooks = collect();

        if (Schema::hasTable('borrowings')) {
            try {
                $borrowings = DB::table('borrowings')
                    ->join('books', 'borrowings.book_id', '=', 'books.id')
                    ->where('borrowings.user_id', $user->id)
                    ->select('borrowings.*', 'books.title', 'books.author', 'books.cover_image')
                    ->get();

                $stats = [
                    'borrowed_books' => $borrowings->where('status', 'borrowed')->count(),
                    'due_soon' => $borrowings->where('status', 'borrowed')
                        ->where('due_date', '>=', now()->format('Y-m-d'))
                        ->where('due_date', '<=', now()->addDays(3)->format('Y-m-d'))->count(),
                    'overdue' => $borrowings->where('status', 'borrowed')
                        ->where('due_date', '<', now()->format('Y-m-d'))->count(),
                    'total_read' => $borrowings->where('status', 'returned')->count(),
                    'fine_amount' => $borrowings->where('status', 'borrowed')->sum('fine_amount'),
                    'favorites' => 0,
                ];

                $recentBorrowings = $borrowings->sortByDesc('borrowed_date')->take(5);
                $dueSoonBooks = $borrowings->where('status', 'borrowed')
                    ->where('due_date', '>=', now()->format('Y-m-d'))
                    ->where('due_date', '<=', now()->addDays(3)->format('Y-m-d'))
                    ->sortBy('due_date');
                $overdueBooks = $borrowings->where('status', 'borrowed')
                    ->where('due_date', '<', now()->format('Y-m-d'))
                    ->sortBy('due_date');

            } catch (\Exception $e) {
                // Keep defaults
            }
        }

        if (Schema::hasTable('favorites')) {
            $stats['favorites'] = DB::table('favorites')->where('user_id', $user->id)->count();
        }

        // Get recommended books safely
        $recommendedBooks = collect();
        if (Schema::hasTable('books')) {
            try {
                $bookColumns = Schema::getColumnListing('books');

                $query = Book::query();

                // Only add conditions if columns exist
                if (in_array('status', $bookColumns)) {
                    $query->where('status', 'available');
                }

                if (in_array('available_copies', $bookColumns)) {
                    $query->where('available_copies', '>', 0);
                }

                $recommendedBooks = $query->inRandomOrder()->limit(4)->get();

            } catch (\Exception $e) {
                // Fallback to basic query
                $recommendedBooks = Book::inRandomOrder()->limit(4)->get();
            }
        }

        return view('user.dashboard', compact(
            'stats', 'recentBorrowings', 'dueSoonBooks',
            'overdueBooks', 'recommendedBooks'
        ));
    }
}
