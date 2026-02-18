<?php

namespace Webkul\Tender\Filament\Resources\TenderTaskResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Tender\Filament\Resources\TenderTaskResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class ViewTenderTask extends ViewRecord
{
    use HasChatter;

    protected static string $resource = TenderTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
