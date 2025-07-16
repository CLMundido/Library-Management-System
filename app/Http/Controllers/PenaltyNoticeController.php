<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Penalty;

class PenaltyNoticeController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $penalties = Penalty::with(['borrowRecord.book'])
            ->whereHas('borrowRecord', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        $total = $penalties->where('paid', false)->sum('amount');

        return view('penalty-notice', compact('penalties', 'total'));
    }
}

