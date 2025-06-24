<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\BookController as UserBookController;
use App\Http\Controllers\User\CategoryController as UserCategoryController;
use App\Http\Controllers\User\BorrowingController as UserBorrowingController;
use App\Http\Controllers\User\FavoriteController as UserFavoriteController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');
});

// Admin Routes (unchanged, as per your request not to touch admin code)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('books', AdminBookController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('students', AdminStudentController::class);
    Route::resource('borrowings', AdminBorrowingController::class);
    Route::get('borrowings/filter/overdue', [AdminBorrowingController::class, 'overdue'])->name('borrowings.overdue');
    Route::post('borrowings/{borrowing}/return', [AdminBorrowingController::class, 'returnBook'])->name('borrowings.return');
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [AdminTransactionController::class, 'index'])->name('index');
        Route::get('/create', [AdminTransactionController::class, 'create'])->name('create');
        Route::post('/', [AdminTransactionController::class, 'store'])->name('store');
        Route::get('/{borrowing}', [AdminTransactionController::class, 'show'])->name('show');
        Route::post('/{borrowing}/return', [AdminTransactionController::class, 'returnBook'])->name('return');
        Route::post('/{borrowing}/renew', [AdminTransactionController::class, 'renew'])->name('renew');
        Route::get('/filter/overdue', [AdminTransactionController::class, 'overdue'])->name('overdue');
    });
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/books', [AdminReportController::class, 'books'])->name('books');
        Route::get('/students', [AdminReportController::class, 'students'])->name('students');
        Route::get('/borrowings', [AdminReportController::class, 'borrowings'])->name('borrowings');
        Route::get('/overdue', [AdminReportController::class, 'overdue'])->name('overdue');
        Route::get('/export/{type}', [AdminReportController::class, 'export'])->name('export');
    });
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminSettingController::class, 'index'])->name('index');
        Route::put('/', [AdminSettingController::class, 'update'])->name('update');
        Route::delete('/reset', [AdminSettingController::class, 'reset'])->name('reset');
    });
});

// User Routes (focused on fixing the 'user.borrowings.index' error)
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/books', [UserBookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [UserBookController::class, 'show'])->name('books.show');
    Route::get('/categories', [UserCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [UserCategoryController::class, 'show'])->name('categories.show');

    // FIX: Changed 'name('borrowings')' to 'name('borrowings.index')' for consistency
    Route::get('/borrowings', [UserBorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/history', [UserBorrowingController::class, 'history'])->name('borrowings.history');
    Route::post('/borrowings/{borrowing}/renew', [UserBorrowingController::class, 'renew'])->name('borrowings.renew');
    // Added the missing POST route for returning books under the user namespace
    Route::post('/borrowings/{borrowing}/return', [UserBorrowingController::class, 'returnBook'])->name('borrowings.return');

    Route::post('/user/books/{book}/borrow', [UserBookController::class, 'borrow'])->name('books.borrow');

    // Favorites routes - fixed for standard Laravel resource-like naming
    Route::get('/favorites', [UserFavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{book}', [UserFavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/favorites/{book}', [UserFavoriteController::class, 'remove'])->name('favorites.remove');

    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/books', [UserBookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [UserBookController::class, 'show'])->name('books.show');
    Route::post('/books/{book}/borrow', [UserBookController::class, 'borrow'])->name('books.borrow');
    Route::get('/categories', [UserCategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [UserCategoryController::class, 'show'])->name('categories.show');
    Route::get('/borrowings', [UserBorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/history', [UserBorrowingController::class, 'history'])->name('borrowings.history');
    Route::post('/borrowings/{borrowing}/renew', [UserBorrowingController::class, 'renew'])->name('borrowings.renew');
    Route::post('/borrowings/{borrowing}/return', [UserBorrowingController::class, 'returnBook'])->name('borrowings.return'); // Added this for consistency

    Route::post('/user/books/{book}/borrow', [UserBookController::class, 'borrow'])->name('books.borrow');
    Route::get('/favorites', [UserFavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{book}', [UserFavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/favorites/{book}', [UserFavoriteController::class, 'remove'])->name('favorites.remove');
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');
});


Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::post('/books/{book}/borrow', [UserBookController::class, 'borrow'])->name('api.books.borrow');
    Route::post('/favorites/{book}/toggle', [UserFavoriteController::class, 'toggle'])->name('api.favorites.toggle');
    Route::post('/borrowings/{borrowing}/renew', [UserBorrowingController::class, 'renew'])->name('api.borrowings.renew');
    Route::post('/borrowings/{borrowing}/return', [UserBorrowingController::class, 'returnBook'])->name('api.borrowings.return');
});

Route::fallback(function () {
    return redirect()->route('welcome')->with('error', 'Page not found. Redirected to home.');
});
