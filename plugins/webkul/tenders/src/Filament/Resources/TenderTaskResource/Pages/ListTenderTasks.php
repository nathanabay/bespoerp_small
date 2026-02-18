<?php

namespace Webkul\Tender\Filament\Resources\TenderTaskResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Webkul\Tender\Filament\Resources\TenderTaskResource;

class ListTenderTasks extends ListRecords
{
    protected static string $resource = TenderTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
