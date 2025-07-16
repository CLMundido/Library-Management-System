<?php

// namespace App\Http\Controllers;

// use App\Models\Book;
// use App\Models\BorrowRequest;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Request;

// class BorrowRequestController extends Controller
// {
//     public function index()
//     {
//         // Get all books, even with 0 copies
//         $books = Book::all();

//         return view('borrow-books', compact('books'));
//     }

//     public function store(Book $book, Request $request)
//     {
//         // dd(Auth::id(), auth()->user(), $request->user());

//         if ($book->copies < 1) {
//             return redirect()->route('borrow-books')->with('error', 'Book not available.');
//         }

//         $borrowRequest = new BorrowRequest();
//         $borrowRequest->user_id = Auth::id();
//         $borrowRequest->book_id = $book->id;
//         $borrowRequest->request_date = now();
//         $borrowRequest->status = 'pending';
//         $borrowRequest->save();


//         return redirect()->route('borrow-books')->with('success', 'Borrow request submitted!');
//     }
// }
