<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget;
use App\Models\User;
use App\Models\Book;
use App\Models\BookCategory;

class TotalWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = '6';

    protected function getStats(): array
    {
        $totalUsers = [
            Card::make('Total Users', User::whereHas('roles', function ($query) {
                $query->where('name', 'user'); // count only users with role 'user'
            })->count())
                ->description('Registered borrowers')
                ->icon('heroicon-o-users')
                ->color('success'),
        ];

        $totalBooks = [
            Card::make('Total Books', Book::count())
                ->description('All books in inventory')
                ->icon('heroicon-o-book-open')
                ->color('primary'),
        ];

        $totalBookCategory = [
            Card::make('Total Books', BookCategory::count())
                ->description('All books category')
                ->icon('heroicon-o-book-open')
                ->color('primary'),
        ];

        return [
            ...$totalUsers,
            ...$totalBooks,
            ...$totalBookCategory,
        ];
    }
}
