<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\BorrowRecord;
use Carbon\Carbon;

class OverdueBooksWidget extends StatsOverviewWidget
{
    // protected static ?int $sort = 5;

    // protected int|string|array $columnSpan = '6';

    // protected function getCards(): array
    // {
    //     return [
    //         Card::make('Overdue Books', BorrowRecord::whereDate('due_date', '<', Carbon::now())->whereNull('return_date')->count())
    //             ->description('Books past their due date')
    //             ->icon('heroicon-o-exclamation-circle')
    //             ->color('danger'),
    //     ];
    // }
}
