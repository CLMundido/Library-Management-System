<?php

namespace App\Filament\Resources\BorrowRecordResource\Pages;

use App\Filament\Resources\BorrowRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBorrowRecords extends ListRecords
{
    protected static string $resource = BorrowRecordResource::class;

    protected function canCreate(): bool
    {
        return false; // 👈 disables the "Create Borrow Request" button
    }
}
