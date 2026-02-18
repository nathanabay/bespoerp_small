<?php

namespace Webkul\Tender\Filament\Resources\PerformanceBondResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Tender\Filament\Resources\PerformanceBondResource;
use Webkul\Chatter\Filament\Pages\Concerns\HasChatter;

class ViewPerformanceBond extends ViewRecord
{
    use HasChatter;

    protected static string $resource = PerformanceBondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
