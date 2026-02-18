<?php

namespace Webkul\Tender\Filament\Resources\CostEstimationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Tender\Filament\Resources\CostEstimationResource;

class ListCostEstimations extends ListRecords
{
    protected static string $resource = CostEstimationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
