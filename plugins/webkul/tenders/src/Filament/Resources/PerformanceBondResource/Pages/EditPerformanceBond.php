<?php

namespace Webkul\Tender\Filament\Resources\PerformanceBondResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Tender\Filament\Resources\PerformanceBondResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class EditPerformanceBond extends EditRecord
{
    use HasChatter;

    protected static string $resource = PerformanceBondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
