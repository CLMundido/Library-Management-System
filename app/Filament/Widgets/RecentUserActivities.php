<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class RecentUserActivities extends BaseWidget
{
    protected static ?string $heading = 'Recent User Activities';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = '6';

    protected function getTableQuery(): Builder
    {
        return Activity::query()
            ->whereHas('causer', function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', 'user'); // Adjust to your borrower/user role name
                });
            })
            ->latest()
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('causer.name')
                ->label('User')
                ->searchable(),

            TextColumn::make('description')
                ->label('Action')
                ->formatStateUsing(fn (string $state) => ucfirst($state)),

            TextColumn::make('created_at')
                ->label('Time')
                ->since()
                ->sortable(),
        ];
    }
}
