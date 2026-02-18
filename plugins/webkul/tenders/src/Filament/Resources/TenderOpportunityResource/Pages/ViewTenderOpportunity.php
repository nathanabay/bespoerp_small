<?php

namespace Webkul\Tender\Filament\Resources\TenderOpportunityResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Tender\Filament\Resources\TenderOpportunityResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class ViewTenderOpportunity extends ViewRecord
{
    use HasChatter;

    protected static string $resource = TenderOpportunityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
