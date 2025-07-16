<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MyBorrowedBooksController;
use App\Http\Controllers\BorrowBookController;
use App\Http\Controllers\BorrowingHistoryController;
use App\Http\Controllers\ReadeBooksController;
use App\Http\Controllers\PenaltyNoticeController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->hasRole('admin')){
            return view('admin.dashboard');
        }

        if ($user->hasRole('user')){
            return view('dashboard');
        }

        abort(403);
    })->name('dashboard');
});

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['web', 'guest', 'recaptcha']) // 👈 add this
    ->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/borrow-books', [BorrowBookController::class, 'index'])->name('borrow-books');
    Route::post('/borrow-books/{book}', [BorrowBookController::class, 'store'])->name('borrow-books.store');
});

Route::get('/borrow-books', [BorrowBookController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('borrow-books');

Route::post('/borrow-books/{book}', [BorrowBookController::class, 'store'])
    ->middleware(['auth'])
    ->name('borrow-books.store');

// Route::post('/borrow-request/{book}', [BorrowRequestController::class, 'store'])
//     ->middleware(['auth', 'verified'])
//     ->name('borrow.request');

Route::get('/my-borrowed-books', [MyBorrowedBooksController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('my-borrowed-books');

Route::get('/borrowing-history', [BorrowingHistoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('borrowing-history');

Route::get('/read-ebooks', [ReadeBooksController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('read-ebooks');

Route::get('/penalty-notice', [PenaltyNoticeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('penalty-notice');

// // Dashboard
// Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// // Book Management
// Route::get('/book-catalog', [BookController::class, 'catalog'])->name('book-catalog');
// Route::get('/books/{id}', [BookController::class, 'show'])->name('book.show');
// Route::get('/read-ebooks', [BookController::class, 'ebooks'])->name('read-ebooks');

// // Borrowing System
// Route::get('/borrow-books', [BorrowController::class, 'index'])->name('borrow-books');
// Route::post('/borrow/{bookId}', [BorrowController::class, 'borrow'])->name('book.borrow');
// Route::get('/my-borrowed-books', [BorrowController::class, 'myBooks'])->name('my-borrowed-books');
// Route::post('/renew/{borrowingId}', [BorrowController::class, 'renew'])->name('book.renew');
// Route::get('/return-books', [BorrowController::class, 'returnBooks'])->name('return-books');
// Route::post('/return/{borrowingId}', [BorrowController::class, 'return'])->name('book.return');
// Route::get('/borrowing-history', [BorrowController::class, 'history'])->name('borrowing-history');
// Route::get('/penalty-notice', [BorrowController::class, 'penalties'])->name('penalty-notice');

// // User Management
// Route::get('/profile', [UserController::class, 'profile'])->name('profile');
// Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
// Route::put('/profile/password', [UserController::class, 'changePassword'])->name('profile.password');
// Route::get('/settings', [UserController::class, 'settings'])->name('settings');
// Route::put('/settings', [UserController::class, 'updateSettings'])->name('settings.update');

// // Notifications
// Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
// Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
// Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
// Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
