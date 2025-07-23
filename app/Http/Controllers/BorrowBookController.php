<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowBookController extends Controller
{
    public function index()
    {
        $search = request('search');

        $books = Book::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            })
            ->orderByDesc('copies') // ✅ Available books first, out of stock last
            ->get();

        return view('borrow-books', compact('books'));
    }

    public function store(Request $request, Book $book)
    {
        if ($book->copies < 1) {
            return back()->with('error', 'This book is currently out of stock.');
        }

        $validated = $request->validate([
            'return_date' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addDays(15)->toDateString(),
        ]);

        BorrowRequest::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'status' => 'pending',
            'request_date' => now(),
            'return_date' => $validated['return_date'],
        ]);

        return back()->with('success', 'Borrow request submitted successfully!');
    }
}
