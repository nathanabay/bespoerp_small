<?php

namespace Webkul\Tender\Filament\Pages;

use Filament\Pages\Page;
use Webkul\Tender\Models\TenderOpportunity;
use Webkul\Tender\Enums\TenderOpportunityStatusEnum;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;

class TenderBoard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string $view = 'tenders::filament.pages.tender-board';

    protected static ?string $navigationLabel = 'Tender Board';

    protected static ?string $navigationGroup = 'Tender Management';

    protected static ?int $navigationSort = 2;

    public function getStatuses(): array
    {
        return TenderOpportunityStatusEnum::cases();
    }

    public function getRecords(): Collection
    {
        return TenderOpportunity::query()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($record) => $record->status->value);
    }

    public function updateStatus($recordId, $status)
    {
        $record = TenderOpportunity::find($recordId);

        if ($record) {
            $record->update(['status' => $status]);
            
            Notification::make()
                ->title('Status Updated')
                ->success()
                ->send();
        }
    }
    
    public function getTitle(): string
    {
        return 'Tender Pipeline';
    }
}
