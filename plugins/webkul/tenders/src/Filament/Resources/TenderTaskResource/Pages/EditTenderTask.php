<?php

namespace Webkul\Tender\Filament\Resources\TenderTaskResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Tender\Filament\Resources\TenderTaskResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class EditTenderTask extends EditRecord
{
    use HasChatter;

    protected static string $resource = TenderTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
