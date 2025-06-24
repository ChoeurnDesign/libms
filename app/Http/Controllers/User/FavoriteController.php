<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Book; // Ensure this is imported if you need to access Book model directly
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Show user's favorite books
    public function index()
    {
        // Eager load the book relationship for all favorites
        $favorites = Favorite::with('book')->where('user_id', Auth::id())->get();

        // Calculate stats
        // Make sure to access properties safely using null coalescing operator or optional helper
        $total_favorites = $favorites->count();
        $available_favorites = $favorites->filter(function ($fav) {
            $book = $fav->book;
            // Check if book exists and if its availability status is true or available_quantity > 0
            return $book && (
                ($book->is_available ?? false) || // Assuming 'is_available' exists on Book model (recommended)
                ($book->available_quantity > 0)    // Fallback or if you track quantity
            );
        })->count();

        $borrowed_favorites = $favorites->filter(function ($fav) {
            $book = $fav->book;
            // Check if book exists and if it's not available or available_quantity is 0
            return $book && (
                !($book->is_available ?? true) || // Assuming 'is_available' and checking if it's false
                ($book->available_quantity == 0) // Fallback or if you track quantity
            );
        })->count();

        $stats = [
            'total_favorites' => $total_favorites,
            'available_favorites' => $available_favorites,
            'borrowed_favorites' => $borrowed_favorites,
        ];

        // Pass favorite_id and book details to Blade for delete forms and display
        $favoriteBooks = $favorites->map(function ($fav) {
            // Safely access book properties
            $book = $fav->book;
            return (object)[
                'favorite_id' => $fav->id,
                'book_id' => $book->id ?? null,
                'book_title' => $book->title ?? null,
                'book_author' => $book->author ?? null,
                'book_cover' => isset($book->cover_url) ? asset($book->cover_url) : null, // Use cover_url for consistency
                // Add availability status for display if needed in favorites.index
                'is_available' => $book->is_available ?? false,
                'available_quantity' => $book->available_quantity ?? 0,
                'quantity' => $book->quantity ?? 0,
            ];
        });

        return view('user.favorites.index', compact('stats', 'favoriteBooks'));
    }

    // Add a book to favorites (kept for direct form submission if needed, but toggle is preferred)
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
        ]);

        return back()->with('success', 'Book added to favorites!');
    }

    // Remove a book from favorites (alias for RESTful destroy)
    public function remove($id)
    {
        $favorite = Favorite::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $favorite->delete();

        return back()->with('success', 'Favorite removed.');
    }

    // Toggles a book's favorite status via AJAX
    public function toggle($bookId)
    {
        $userId = Auth::id(); // Use Auth::id() for consistency and clarity

        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
        }

        $favorite = Favorite::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Book removed from favorites.';
            $isFavorited = false;
        } else {
            Favorite::create([
                'user_id' => $userId,
                'book_id' => $bookId,
            ]);
            $message = 'Book added to favorites!';
            $isFavorited = true;
        }

        // Return a JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorited' => $isFavorited // Indicate current favorite status
        ]);
    }
}
