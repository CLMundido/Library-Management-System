<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowRecordResource\Pages;
use App\Filament\Resources\BorrowRecordResource\RelationManagers;
use App\Models\BorrowRecord;
use App\Models\Book;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BorrowRecordResource extends Resource
{
    protected static ?string $model = BorrowRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = "Borrow Records";

    protected static ?string $modelLabel = "Borrow Record";

    protected static ?string $pluralModelLabel = "Borrow Records";

    protected static ?string $navigationGroup = "Book Transaction";

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student')
                    ->getStateUsing(function ($record) {
                        return $record->user ? $record->user->name : 'Unknown';
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->join('users', 'borrow_records.user_id', '=', 'users.id')
                            ->orderBy('users.name', $direction);
                    })
                    ->searchable(['users.name']),

                Tables\Columns\TextColumn::make('user.user_id')
                    ->label('Student #')
                    ->formatStateUsing(function ($record) {
                        $user = \App\Models\User::find($record->user_id);
                        return $user ? $user->user_id : 'N/A';
                    })
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('book.title')
                    ->label('Book Title')
                    ->formatStateUsing(function ($record) {
                        $book = \App\Models\Book::find($record->book_id);
                        return $book ? $book->title : 'Unknown';
                    })
                    ->searchable()
                    ->wrap()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('book.author')
                    ->label('Author')
                    ->formatStateUsing(function ($record) {
                        $book = \App\Models\Book::find($record->book_id);
                        return $book ? $book->author : 'Unknown';
                    })
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('borrow_date')
                    ->label('Borrowed')
                    ->date('M j, Y g:i A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Due Date')
                    ->date('M j, Y')
                    ->sortable()
                    ->color(fn($record) => match (true) {
                        $record->status === 'Returned' => 'success',
                        Carbon::parse($record->due_date)->isPast() && $record->status !== 'Returned' => 'danger',
                        Carbon::parse($record->due_date)->diffInDays(now()) <= 3 => 'warning',
                        default => 'gray'
                    }),

                Tables\Columns\TextColumn::make('return_date')
                    ->label('Returned')
                    ->date('M j, Y g:i A')
                    ->sortable()
                    ->placeholder('Not returned')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('days_overdue')
                    ->label('Days Overdue')
                    ->state(function ($record) {
                        // If the book has a return date, compare it with due date
                        if ($record->return_date) {
                            $dueDate = Carbon::parse($record->due_date)->startOfDay();
                            $returnedDate = Carbon::parse($record->return_date)->startOfDay();

                            // If returned date surpasses (is after) due date, show days overdue
                            if ($returnedDate->greaterThan($dueDate)) {
                                $days = $dueDate->diffInDays($returnedDate);
                                return $days . ' day' . ($days !== 1 ? 's' : '');
                            }

                            // If returned on time or early, show nothing
                            return '';
                        }

                        // If not returned yet, calculate current overdue status
                        $dueDate = Carbon::parse($record->due_date)->startOfDay();
                        $today = Carbon::now()->startOfDay();

                        if ($today->greaterThan($dueDate)) {
                            $days = $dueDate->diffInDays($today);
                            return $days . ' day' . ($days !== 1 ? 's' : '');
                        }

                        // Not overdue yet, show nothing
                        return '';
                    })
                    ->color(function ($state) {
                        if (empty($state)) {
                            return 'gray';
                        }
                        return 'danger';
                    })
                    ->badge()
                    ->placeholder('-')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        // Sort by actual days overdue calculation
                        return $query->selectRaw('
                            *, 
                            CASE 
                                WHEN return_date IS NOT NULL AND DATE(return_date) > DATE(due_date) THEN DATEDIFF(DATE(return_date), DATE(due_date))
                                WHEN return_date IS NULL AND DATE(due_date) < CURDATE() THEN DATEDIFF(CURDATE(), DATE(due_date))
                                ELSE 0
                            END as calculated_days_overdue
                        ')
                            ->orderBy('calculated_days_overdue', $direction);
                    })
                    ->toggleable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'Borrowed',
                        'success' => 'Returned',
                        'danger' => 'Overdue',
                        'warning' => 'Lost',
                        'gray' => 'Damaged',
                    ])
                    ->icons([
                        'heroicon-o-arrow-right-circle' => 'Borrowed',
                        'heroicon-o-check-circle' => 'Returned',
                        'heroicon-o-exclamation-triangle' => 'Overdue',
                        'heroicon-o-x-circle' => 'Lost',
                        'heroicon-o-wrench-screwdriver' => 'Damaged',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Record Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Borrowed' => 'Borrowed',
                        'Returned' => 'Returned',
                        'Overdue' => 'Overdue',
                        'Lost' => 'Lost',
                        'Damaged' => 'Damaged',
                    ])
                    ->multiple(),

                Filter::make('overdue')
                    ->label('Overdue Books')
                    ->query(fn(Builder $query) => $query->where('due_date', '<', now())->where('status', '!=', 'Returned'))
                    ->indicator('Overdue'),

                Filter::make('due_soon')
                    ->label('Due Soon (3 days)')
                    ->query(fn(Builder $query) => $query->whereBetween('due_date', [now(), now()->addDays(3)])->where('status', '!=', 'Returned'))
                    ->indicator('Due Soon'),

                Filter::make('not_returned')
                    ->label('Not Returned')
                    ->query(fn(Builder $query) => $query->whereNull('return_date'))
                    ->indicator('Not Returned'),

                Filter::make('borrowed_date')
                    ->form([
                        Forms\Components\DatePicker::make('borrowed_from')
                            ->label('Borrowed From'),
                        Forms\Components\DatePicker::make('borrowed_until')
                            ->label('Borrowed Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['borrowed_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('borrow_date', '>=', $date),
                            )
                            ->when(
                                $data['borrowed_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('borrow_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['borrowed_from'] ?? null) {
                            $indicators[] = 'Borrowed from ' . Carbon::parse($data['borrowed_from'])->toFormattedDateString();
                        }
                        if ($data['borrowed_until'] ?? null) {
                            $indicators[] = 'Borrowed until ' . Carbon::parse($data['borrowed_until'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_returned')
                    ->label('Mark Returned')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->status !== 'Returned' && empty($record->return_date))
                    ->action(function ($record) {
                        $record->update([
                            'return_date' => now(),
                            'status' => 'Returned',
                        ]);

                        // ✅ Increment book copies
                        if ($record->book) {
                            $record->book->increment('copies');
                        }
                    })
                    ->requiresConfirmation(),

                Tables\Actions\Action::make('mark_overdue')
                    ->label('Mark Overdue')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->visible(fn($record) => $record->status === 'Borrowed' && Carbon::parse($record->due_date)->isPast())
                    ->action(function ($record) {
                        $record->update(['status' => 'Overdue']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_returned')
                        ->label('Mark as Returned')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'return_date' => now(),
                                    'status' => 'Returned',
                                ]);

                                // ✅ Increment book copies
                                if ($record->book) {
                                    $record->book->increment('copies');
                                }
                            });
                        })
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('mark_overdue')
                        ->label('Mark as Overdue')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                if ($record->status !== 'Returned') {
                                    $record->update(['status' => 'Overdue']);
                                }
                            });
                        })
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('borrow_date', 'desc')
            ->striped()
            ->poll('30s');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBorrowRecords::route('/'),
            'create' => Pages\CreateBorrowRecord::route('/create'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '!=', 'Returned')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $overdueCount = static::getModel()::where('due_date', '<', now())->where('status', '!=', 'Returned')->count();
        return $overdueCount > 0 ? 'danger' : 'primary';
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        $user = \App\Models\User::find($record->user_id);
        $book = \App\Models\Book::find($record->book_id);

        return ($user ? $user->name : 'Unknown') .
            ' - ' .
            ($book ? $book->title : 'Unknown Book');
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Status' => $record->status,
            'Due Date' => $record->due_date ? Carbon::parse($record->due_date)->format('M j, Y') : 'N/A',
            'Borrowed Date' => $record->borrow_date ? Carbon::parse($record->borrow_date)->format('M j, Y') : 'N/A',
        ];
    }
}
