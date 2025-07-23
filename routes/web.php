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
    Route::get('/borrow-books', [BorrowBookController::class, 'index'])->name('borrow-books.index');
    Route::post('/borrow-books/{book}', [BorrowBookController::class, 'store'])->name('borrow-books.store');
});

Route::get('/borrow-books', [BorrowBookController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('borrow-books');

Route::post('/borrow-books/{book}', [BorrowBookController::class, 'store'])
    ->middleware(['auth'])
    ->name('borrow-books.store');

Route::get('/my-borrowed-books', [MyBorrowedBooksController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('my-borrowed-books');

Route::get('/borrowing-history', [BorrowingHistoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('borrowing-history');

Route::get('/penalty-notice', [PenaltyNoticeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('penalty-notice');