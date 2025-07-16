<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\BorrowRequest;

class PendingBorrowRequestsWidget extends StatsOverviewWidget
{
    // protected static ?int $sort = 3;

    // protected int|string|array $columnSpan = '6';

    // protected function getCards(): array
    // {
    //     return [
    //         Card::make('Pending Borrow Requests', BorrowRequest::where('status', 'pending')->count())
    //             ->description('Awaiting approval')
    //             ->icon('heroicon-o-clock')
    //             ->color('warning'),
    //     ];
    // }
}
