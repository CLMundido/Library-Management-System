<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;

class MostBorrowedBooks extends BaseWidget
{
    protected static ?string $heading = 'Top 5 Most Borrowed Books';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = '6';

    protected function getTableQuery(): Builder
    {
        return Book::withCount('borrowRecords')
            ->orderByDesc('borrow_records_count')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')
                ->label('Book Title')
                ->sortable()
                ->searchable(),

            TextColumn::make('borrow_records_count')
                ->label('Total Borrows')
                ->sortable(),
        ];
    }
}
