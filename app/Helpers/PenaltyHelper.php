<?php

namespace App\Helpers;

use App\Models\BorrowRecord;
use App\Models\Penalty;
use Carbon\Carbon;

class PenaltyHelper
{
    public static function checkOverdues(): void
    {
        $penaltyRate = 10;
        $today = Carbon::now()->startOfDay();

        $records = BorrowRecord::whereNull('return_date')
            ->whereDate('due_date', '<', $today)
            ->with('student', 'book')
            ->get();

        foreach ($records as $record) {
            $due = Carbon::parse($record->due_date)->startOfDay();
            $daysLate = $due->diffInDays($today);

            $existing = Penalty::where('borrow_record_id', $record->id)->first();

            if ($existing) {
                $existing->update([
                    'days_late' => $daysLate,
                    'amount' => $daysLate * $penaltyRate,
                ]);
            } else {
                Penalty::create([
                    'borrow_record_id' => $record->id,
                    'days_late' => $daysLate,
                    'amount' => $daysLate * $penaltyRate,
                    'reason' => 'Overdue book return',
                    'paid' => false,
                ]);
            }
        }
    }
}
