<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\BorrowRequest;
use App\Models\BorrowRecord;
use App\Models\Penalty;
use Carbon\Carbon;

class TransactionWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = '6';

    protected function getStats(): array
    {
        $total = Penalty::whereMonth('created_at', now()->month)->sum('amount');

        $borrowRequest = [
            Card::make('Pending Borrow Requests', BorrowRequest::where('status', 'pending')->count())
                ->description('Awaiting approval')
                ->icon('heroicon-o-clock')
                ->color('warning'),
        ];

        $borrowRecord = [
            Card::make('Overdue Books', BorrowRecord::whereDate('due_date', '<', Carbon::now())->whereNull('return_date')->count())
                ->description('Books past their due date')
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];

        $totalPenalties = [
            Card::make('Penalties This Month', '₱' . number_format($total, 2))
                ->description('Total collected this month')
                ->icon('heroicon-o-currency-dollar')
                ->color('gray'),
        ];

        return [
            ...$borrowRequest,
            ...$borrowRecord,
            ...$totalPenalties,
        ];
    }
}
