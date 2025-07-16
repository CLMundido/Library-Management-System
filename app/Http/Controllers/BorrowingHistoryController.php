<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRecord;

class BorrowingHistoryController extends Controller
{
    public function index()
    {
        $records = BorrowRecord::with('book')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('borrowing-history', compact('records'));
    }
}
