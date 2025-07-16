<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowRequestResource\Pages;
use App\Models\BorrowRequest; // Changed model to BorrowRequest
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
use Illuminate\Database\Eloquent\SoftDeletingScope; // Keep if SoftDeletes trait is used on model
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\BorrowRequestStatusMail;

class BorrowRequestResource extends Resource // Changed class name
{
    protected static ?string $model = BorrowRequest::class; // Changed model

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass'; // Changed icon to reflect requests

    protected static ?string $navigationLabel = "Borrow Requests"; // Changed navigation label

    protected static ?string $modelLabel = "Borrow Request"; // Changed model label

    protected static ?string $pluralModelLabel = "Borrow Requests"; // Changed plural label

    protected static ?string $navigationGroup = "Book Transaction";

    protected static ?int $navigationSort = 1; // Changed sort to be before Borrow Records

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.user_id')
                    ->label('Student #')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('book.title')
                    ->label('Book Title')
                    ->searchable()
                    ->wrap()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('book.author')
                    ->label('Author')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('request_date')
                    ->label('Request Date')
                    ->date('M j, Y g:i A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('return_date')
                    ->label('Returned')
                    ->date('M j, Y')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'gray' => 'cancelled',
                    ])
                    ->icons([
                        'heroicon-o-arrow-path' => 'pending',
                        'heroicon-o-check-circle' => 'approved',
                        'heroicon-o-x-circle' => 'rejected',
                        'heroicon-o-minus-circle' => 'cancelled',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested At')
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
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple()
                    ->label('Filter by Status'),

                Filter::make('request_date')
                    ->label('Request Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('requested_from')
                            ->label('From'),
                        Forms\Components\DatePicker::make('requested_until')
                            ->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['requested_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('request_date', '>=', $date),
                            )
                            ->when(
                                $data['requested_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('request_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['requested_from'] ?? null) {
                            $indicators[] = 'Requested from ' . Carbon::parse($data['requested_from'])->toFormattedDateString();
                        }
                        if ($data['requested_until'] ?? null) {
                            $indicators[] = 'Requested until ' . Carbon::parse($data['requested_until'])->toFormattedDateString();
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(BorrowRequest $record) => $record->status === 'pending')
                    ->action(function (BorrowRequest $record) {
                        $record->update(['status' => 'approved']);

                        \App\Models\BorrowRecord::create([
                            'user_id' => $record->user_id,
                            'book_id' => $record->book_id,
                            'borrow_date' => now(),
                            'due_date' => $record->return_date,
                            'status' => "borrowed",
                        ]);

                        $book = \App\Models\Book::find($record->book_id);
                        if ($book && $book->copies > 0) {
                            $book->decrement('copies');
                        }

                        // Send approval email
                        Mail::to($record->user->email)->send(new BorrowRequestStatusMail($record));
                    })
                    ->requiresConfirmation(),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(BorrowRequest $record) => $record->status === 'pending')
                    ->action(function (BorrowRequest $record) {
                        $record->update(['status' => 'rejected']);

                        // Send rejection email
                        Mail::to($record->user->email)->send(new BorrowRequestStatusMail($record));
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve_selected')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function (BorrowRequest $record) {
                                if ($record->status === 'pending') {
                                    $record->update(['status' => 'approved']);
                                    // Step 2: Insert into borrow_records
                                    \App\Models\BorrowRecord::create([
                                        'user_id' => $record->user_id,
                                        'book_id' => $record->book_id,
                                        'borrowed_at' => now(),
                                        'due_date' => now()->addDays(7), // example due date
                                    ]);

                                    // Optional: Decrease book stock
                                    $book = \App\Models\Book::find($record->book_id);
                                    if ($book && $book->copies > 0) {
                                        $book->decrement('copies');
                                    }
                                }
                            });
                        })
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('reject_selected')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function (BorrowRequest $record) {
                                if ($record->status === 'pending') {
                                    $record->update(['status' => 'rejected']);
                                }
                            });
                        })
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('cancel_selected')
                        ->label('Cancel Selected')
                        ->icon('heroicon-o-minus-circle')
                        ->color('gray')
                        ->action(function ($records) {
                            $records->each(function (BorrowRequest $record) {
                                if ($record->status === 'pending') {
                                    $record->update(['status' => 'cancelled']);
                                }
                            });
                        })
                        ->requiresConfirmation(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('request_date', 'desc')
            ->striped()
            ->poll('30s');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBorrowRequests::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $pendingCount = static::getModel()::where('status', 'pending')->count();
        return $pendingCount > 0 ? 'warning' : 'primary';
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return ($record->user?->name ?: 'Unknown Student') .
            ' - ' .
            ($record->book?->title ?: 'Unknown Book') .
            ' (Request)';
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Status' => $record->status,
            'Request Date' => Carbon::parse($record->request_date)->format('M j, Y'),
        ];
    }

    /**
     * Eager load relationships for the table query to avoid N+1 issues.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['user', 'book']);
    }
}
