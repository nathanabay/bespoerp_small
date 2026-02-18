<?php

namespace Webkul\Tender\Filament\Resources\TenderOpportunityResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Tender\Filament\Resources\TenderOpportunityResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class EditTenderOpportunity extends EditRecord
{
    use HasChatter;

    protected static string $resource = TenderOpportunityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
