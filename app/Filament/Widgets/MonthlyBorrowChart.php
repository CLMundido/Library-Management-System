<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\BorrowRecord;
use Carbon\Carbon;

class MonthlyBorrowChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Borrowed Books';

    protected int|string|array $columnSpan = '6';
    
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $data = collect();

        // Get past 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = BorrowRecord::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $data->push([
                'label' => $month->format('M Y'),
                'value' => $count,
            ]);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Borrows',
                    'data' => $data->pluck('value'),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->pluck('label'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
