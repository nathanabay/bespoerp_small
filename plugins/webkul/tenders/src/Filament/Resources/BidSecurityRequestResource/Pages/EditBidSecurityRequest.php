<?php

namespace Webkul\Tender\Filament\Resources\BidSecurityRequestResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Tender\Filament\Resources\BidSecurityRequestResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class EditBidSecurityRequest extends EditRecord
{
    use HasChatter;

    protected static string $resource = BidSecurityRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
