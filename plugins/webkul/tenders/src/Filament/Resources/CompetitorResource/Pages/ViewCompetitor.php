<?php

namespace Webkul\Tender\Filament\Resources\CompetitorResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Tender\Filament\Resources\CompetitorResource;

class ViewCompetitor extends ViewRecord
{
    protected static string $resource = CompetitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
