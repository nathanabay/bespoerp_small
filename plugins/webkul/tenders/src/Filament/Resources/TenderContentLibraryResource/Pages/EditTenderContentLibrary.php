<?php

namespace Webkul\Tender\Filament\Resources\TenderContentLibraryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Tender\Filament\Resources\TenderContentLibraryResource;

class EditTenderContentLibrary extends EditRecord
{
    protected static string $resource = TenderContentLibraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
