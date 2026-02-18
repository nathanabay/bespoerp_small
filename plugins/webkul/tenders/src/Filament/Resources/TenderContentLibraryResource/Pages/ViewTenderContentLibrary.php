<?php

namespace Webkul\Tender\Filament\Resources\TenderContentLibraryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Tender\Filament\Resources\TenderContentLibraryResource;

class ViewTenderContentLibrary extends ViewRecord
{
    protected static string $resource = TenderContentLibraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
