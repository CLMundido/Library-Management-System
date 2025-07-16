<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $borrowedBooks = BorrowRecord::where('user_id', $userId)
            ->where('status', 'borrowed')
            ->whereNull('return_date')
            ->count();

        $overdueBooks = BorrowRecord::where('user_id', $userId)
            ->where('status', 'overdue')
            ->whereNull('return_date')
            ->where('due_date', '<', now())
            ->count();

        $recentBorrowed = BorrowRecord::with('book')
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('borrowedBooks', 'overdueBooks', 'recentBorrowed'));
    }
}