<?php

namespace Webkul\Tender\Filament\Resources\BidDecisionMatrixResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Tender\Filament\Resources\BidDecisionMatrixResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class EditBidDecisionMatrix extends EditRecord
{
    use HasChatter;

    protected static string $resource = BidDecisionMatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
