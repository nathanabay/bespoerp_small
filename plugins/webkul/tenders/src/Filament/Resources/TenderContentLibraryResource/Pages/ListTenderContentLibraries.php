<?php

namespace Webkul\Tender\Filament\Resources\TenderContentLibraryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Tender\Filament\Resources\TenderContentLibraryResource;

class ListTenderContentLibraries extends ListRecords
{
    protected static string $resource = TenderContentLibraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
