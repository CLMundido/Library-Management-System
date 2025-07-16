<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\User;

class TotalUsersWidget extends StatsOverviewWidget
{
    // protected static ?int $sort = 1;

    // protected int|string|array $columnSpan = '6';

    // protected function getCards(): array
    // {
    //     return [
    //         Card::make('Total Users', User::count())
    //             ->description('Registered borrowers')
    //             ->icon('heroicon-o-users')
    //             ->color('success'),
    //     ];
    // }
}
