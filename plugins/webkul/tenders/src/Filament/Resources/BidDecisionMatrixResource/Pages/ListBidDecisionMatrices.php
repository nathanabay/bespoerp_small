<?php

namespace Webkul\Tender\Filament\Resources\BidDecisionMatrixResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Tender\Filament\Resources\BidDecisionMatrixResource;

class ListBidDecisionMatrices extends ListRecords
{
    protected static string $resource = BidDecisionMatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
