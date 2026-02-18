<?php

namespace Webkul\Tender\Filament\Resources\PerformanceBondResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Tender\Filament\Resources\PerformanceBondResource;

class ListPerformanceBonds extends ListRecords
{
    protected static string $resource = PerformanceBondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
