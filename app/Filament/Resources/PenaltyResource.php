<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenaltyResource\Pages;
use App\Models\Penalty;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class PenaltyResource extends Resource
{
    protected static ?string $model = Penalty::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Penalties';
    protected static ?string $modelLabel = 'List of Penalties';
    protected static ?string $navigationGroup = 'Records';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        \App\Helpers\PenaltyHelper::checkOverdues();
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('borrowRecord.user.name')
                    ->label('👨‍🎓 Student')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('borrowRecord.book.title')
                    ->label('📚 Book Title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('days_late')
                    ->label('📆 Days Late')
                    ->sortable()
                    ->formatStateUsing(function (Penalty $record) {
                        $borrow = $record->borrowRecord;
                        if (!$borrow) return '';

                        $dueDate = Carbon::parse($borrow->due_date)->startOfDay();

                        if ($borrow->return_date) {
                            $returnedDate = Carbon::parse($borrow->return_date)->startOfDay();

                            if ($returnedDate->greaterThan($dueDate)) {
                                $days = $dueDate->diffInDays($returnedDate);
                                return $days . ' day' . ($days !== 1 ? 's' : '');
                            }

                            return '0 days';
                        }

                        $today = Carbon::now()->startOfDay();

                        if ($today->greaterThan($dueDate)) {
                            $days = $dueDate->diffInDays($today);
                            return $days . ' day' . ($days !== 1 ? 's' : '');
                        }

                        return '0 days';
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('💰 Penalty')
                    ->money('PHP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reason')
                    ->label('📄 Reason')
                    ->wrap()
                    ->limit(40),

                Tables\Columns\IconColumn::make('paid')
                    ->label('✅ Paid')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('📅 Created At')
                    ->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('paid'),
            ])
            ->actions([
                Action::make('Mark as Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => !$record->paid)
                    ->action(function (Penalty $record) {
                        $record->update(['paid' => true]);

                        Notification::make()
                            ->title('Marked as Paid')
                            ->body('Penalty has been marked as paid.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([]); // disabled bulk delete/edit
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenalties::route('/'),
        ];
    }
}
