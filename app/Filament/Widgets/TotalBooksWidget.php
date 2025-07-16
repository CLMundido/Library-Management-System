<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\Book;

class TotalBooksWidget extends StatsOverviewWidget
{
    // protected static ?int $sort = 2;

    // protected int|string|array $columnSpan = '6';

    // protected function getCards(): array
    // {
    //     return [
    //         Card::make('Total Books', Book::count())
    //             ->description('All books in inventory')
    //             ->icon('heroicon-o-book-open')
    //             ->color('primary'),
    //     ];
    // }
}
