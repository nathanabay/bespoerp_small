<?php

namespace Webkul\Tender\Filament\Resources\CostEstimationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webkul\Tender\Filament\Resources\CostEstimationResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class EditCostEstimation extends EditRecord
{
    use HasChatter;

    protected static string $resource = CostEstimationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
