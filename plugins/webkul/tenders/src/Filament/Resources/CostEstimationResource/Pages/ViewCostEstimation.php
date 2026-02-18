<?php

namespace Webkul\Tender\Filament\Resources\CostEstimationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Tender\Filament\Resources\CostEstimationResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class ViewCostEstimation extends ViewRecord
{
    use HasChatter;

    protected static string $resource = CostEstimationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
