<?php

namespace Webkul\Tender\Filament\Resources\BidDecisionMatrixResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Tender\Filament\Resources\BidDecisionMatrixResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class ViewBidDecisionMatrix extends ViewRecord
{
    use HasChatter;

    protected static string $resource = BidDecisionMatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
