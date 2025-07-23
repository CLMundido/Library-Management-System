<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use App\Models\BookCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = "Books";

    protected static ?string $modelLabel = "Book";

    protected static ?string $pluralModelLabel = "Books";

    protected static ?string $navigationGroup = "Book Management";

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Book Information')
                    ->schema([
                        Forms\Components\FileUpload::make('cover_image')
                            ->label('Upload Cover')
                            ->image()
                            ->imageEditor()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('3:4')
                            ->imageResizeTargetWidth('300')
                            ->imageResizeTargetHeight('400')
                            ->directory('book-covers')
                            ->columnSpan(1),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('isbn')
                                    ->label('ISBN')
                                    ->maxLength(17)
                                    ->required()
                                    ->helperText('Auto-fill book information when a valid ISBN exists in Google Books')
                                    ->unique(ignoreRecord: true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!filled($state)) return;

                                        $response = Http::get("https://www.googleapis.com/books/v1/volumes?q=isbn:{$state}");

                                        if ($response->successful() && $response['totalItems'] > 0) {
                                            $book = $response['items'][0]['volumeInfo'];

                                            $set('title', $book['title'] ?? '');
                                            $set('author', is_array($book['authors']) ? implode(', ', $book['authors']) : ($book['authors'] ?? ''));
                                            $set('description', $book['description'] ?? '');

                                            // Optional: show a toast
                                            \Filament\Notifications\Notification::make()
                                                ->title('Book info fetched from Google Books.')
                                                ->success()
                                                ->send();
                                        } else {
                                            \Filament\Notifications\Notification::make()
                                                ->title('No book found for this ISBN.')
                                                ->warning()
                                                ->send();
                                        }
                                    }),

                                Forms\Components\Select::make('book_category_id')
                                    ->label('Category')
                                    ->relationship('bookCategory', 'name')
                                    ->searchable()
                                    ->required(),
                            ])
                            ->columnSpan(2),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Book Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('author')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('description')
                            ->rows(5)
                            ->columnSpanFull()
                            ->label('Book Description'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Inventory Management')
                    ->schema([
                        Forms\Components\TextInput::make('copies')
                            ->label('Total Copies')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->circular()
                    ->size(50),

                Tables\Columns\TextColumn::make('isbn')
                    ->label('ISBN')
                    ->searchable()
                    ->copyable()
                    ->placeholder('Not set')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('author')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('bookCategory.name')
                    ->label('Category')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('copies')
                    ->label('Total')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\BadgeColumn::make('availability')
                    ->colors([
                        'success' => 'available',
                        'danger' => 'out of stock',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'available',
                        'heroicon-o-x-circle' => 'out of stock',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('book_category_id')
                    ->label('Category')
                    ->options(fn() => BookCategory::pluck('name', 'id')->toArray())
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListBooks::route('/'),
            // 'create' => Pages\CreateBook::route('/create'),
            // 'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 100 ? 'success' : 'primary';
    }
}
