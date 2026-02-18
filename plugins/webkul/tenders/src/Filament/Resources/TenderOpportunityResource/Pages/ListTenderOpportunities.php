<?php

namespace Webkul\Tender\Filament\Resources\TenderOpportunityResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Tender\Filament\Resources\TenderOpportunityResource;

class ListTenderOpportunities extends ListRecords
{
    protected static string $resource = TenderOpportunityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
