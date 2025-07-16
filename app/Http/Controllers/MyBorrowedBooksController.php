<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRecord;
use Illuminate\Support\Facades\Auth;

class MyBorrowedBooksController extends Controller
{
    public function index()
    {
        $borrowedBooks = BorrowRecord::where('user_id', Auth::id())
            ->where('status', 'borrowed')
            // ->where(function ($query) {
            //     $query->whereNull('return_date')
            //         ->orWhere('due_date', '<', now());
            // })
            ->with('book')
            ->latest()
            ->get();

        return view('my-borrowed-books', compact('borrowedBooks'));
    }
}
