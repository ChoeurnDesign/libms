<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Borrowing; // <-- Make sure this import exists!
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $activeBorrowings = collect();
        $stats = [
            'total_active' => 0,
            'due_soon' => 0,
            'overdue' => 0,
            'can_borrow_more' => true,
            'borrowing_limit' => 5,
        ];

        if (Schema::hasTable('borrowings')) {
            try {
                $borrowings = DB::table('borrowings')
                    ->join('books', 'borrowings.book_id', '=', 'books.id')
                    ->where('borrowings.user_id', $user->id)
                    ->where('borrowings.status', 'borrowed')
                    ->select(
                        'borrowings.*',
                        'books.title as book_title',
                        'books.author as book_author',
                        'books.cover_image as book_cover'
                    )
                    ->orderBy('borrowings.due_date')
                    ->get();

                $activeBorrowings = $borrowings;

                $stats['total_active'] = $borrowings->count();
                $stats['due_soon'] = $borrowings->where('due_date', '>=', now()->format('Y-m-d'))
                    ->where('due_date', '<=', now()->addDays(3)->format('Y-m-d'))->count();
                $stats['overdue'] = $borrowings->where('due_date', '<', now()->format('Y-m-d'))->count();
                $stats['can_borrow_more'] = $stats['total_active'] < 5;

            } catch (\Exception $e) {
                // Keep defaults
            }
        }

        return view('user.borrowings.index', compact('activeBorrowings', 'stats'));
    }

    public function history(Request $request)
    {
        $user = Auth::user();

        $borrowingHistory = collect();
        $years = collect();
        $historyStats = [
            'total_borrowed' => 0,
            'total_returned' => 0,
            'total_fines' => 0,
            'current_fines' => 0,
        ];

        if (Schema::hasTable('borrowings')) {
            try {
                $history = DB::table('borrowings')
                    ->join('books', 'borrowings.book_id', '=', 'books.id')
                    ->where('borrowings.user_id', $user->id)
                    ->select(
                        'borrowings.*',
                        'books.title as book_title',
                        'books.author as book_author',
                        'books.cover_image as book_cover'
                    )
                    ->orderBy('borrowings.borrowed_date', 'desc')
                    ->get();

                $borrowingHistory = $history;

                $historyStats = [
                    'total_borrowed' => $history->count(),
                    'total_returned' => $history->where('status', 'returned')->count(),
                    'total_fines' => $history->sum('fine_amount'),
                    'current_fines' => $history->where('status', 'borrowed')->sum('fine_amount'),
                ];

            } catch (\Exception $e) {
                // Keep defaults
            }
        }

        return view('user.borrowings.history', compact('borrowingHistory', 'years', 'historyStats'));
    }

    public function renew($borrowingId)
    {
        $borrowing = Borrowing::findOrFail($borrowingId);

        // Check user
        if ($borrowing->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.']);
        }

        // Example: allow only if status is 'borrowed'
        if ($borrowing->status !== 'borrowed') {
            return response()->json(['success' => false, 'message' => 'Cannot renew a returned borrowing.']);
        }

        // Extend due_date by 14 days
        $borrowing->due_date = Carbon::parse($borrowing->due_date)->addDays(14)->format('Y-m-d');
        $borrowing->renewed_at = now();
        $borrowing->save();

        return response()->json([
            'success' => true,
            'message' => 'Reading time extended! New return date: ' . $borrowing->due_date
        ]);
    }

    public function returnBook($borrowingId)
{
    $borrowing = Borrowing::findOrFail($borrowingId);

    if ($borrowing->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'Unauthorized.']);
    }

    if ($borrowing->status !== 'borrowed') {
        return response()->json(['success' => false, 'message' => 'Book is already returned.']);
    }

    $borrowing->status = 'returned';
    // $borrowing->returned_at = now(); // REMOVE OR COMMENT THIS LINE
    $borrowing->save();

    return response()->json([
        'success' => true,
        'message' => 'Book returned successfully!'
    ]);
}
}
