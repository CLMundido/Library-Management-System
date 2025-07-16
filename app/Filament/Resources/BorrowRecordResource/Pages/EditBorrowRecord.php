<?php

namespace App\Filament\Resources\BorrowRecordResource\Pages;

use App\Filament\Resources\BorrowRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBorrowRecord extends EditRecord
{
    protected static string $resource = BorrowRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
