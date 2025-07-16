<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;

class LowStockBooks extends BaseWidget
{
    protected static ?string $heading = 'Low Stock Books';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = '6';

    protected function getTableQuery(): Builder
    {
        return Book::query()
            ->where('copies', '<=', 2)
            ->orderBy('copies', 'asc');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')
                ->label('Book Title')
                ->searchable(),

            TextColumn::make('copies')
                ->label('Copies Left')
                ->sortable(),
        ];
    }
}
