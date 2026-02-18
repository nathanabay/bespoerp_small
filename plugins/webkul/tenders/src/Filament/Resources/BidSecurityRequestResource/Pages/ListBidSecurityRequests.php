<?php

namespace Webkul\Tender\Filament\Resources\BidSecurityRequestResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Tender\Filament\Resources\BidSecurityRequestResource;

class ListBidSecurityRequests extends ListRecords
{
    protected static string $resource = BidSecurityRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
