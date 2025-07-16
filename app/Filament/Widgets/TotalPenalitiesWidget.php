<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\Penalty;
use Carbon\Carbon;

class TotalPenaltiesWidget extends StatsOverviewWidget
{
    // protected static ?int $sort = 5;

    // protected int|string|array $columnSpan = '6';

    // protected function getCards(): array
    // {
    //     $total = Penalty::whereMonth('created_at', now()->month)->sum('amount');

    //     return [
    //         Card::make('Penalties This Month', '₱' . number_format($total, 2))
    //             ->description('Total collected this month')
    //             ->icon('heroicon-o-currency-dollar')
    //             ->color('gray'),
    //     ];
    // }
}
