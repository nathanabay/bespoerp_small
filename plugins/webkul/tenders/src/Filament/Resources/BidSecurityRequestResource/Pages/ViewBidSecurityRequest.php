<?php

namespace Webkul\Tender\Filament\Resources\BidSecurityRequestResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Tender\Filament\Resources\BidSecurityRequestResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class ViewBidSecurityRequest extends ViewRecord
{
    use HasChatter;

    protected static string $resource = BidSecurityRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
