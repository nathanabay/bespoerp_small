<?php

namespace Webkul\Tender\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum TenderOpportunityStatusEnum: string implements HasLabel, HasColor
{
    case Draft = 'draft';
    case Identified = 'identified';
    case Qualification = 'qualification';
    case BidPreparation = 'bid_preparation';
    case Submitted = 'submitted';
    case Won = 'won';
    case Lost = 'lost';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Identified => 'Identified',
            self::Qualification => 'Qualification',
            self::BidPreparation => 'Bid Preparation',
            self::Submitted => 'Submitted',
            self::Won => 'Won',
            self::Lost => 'Lost',
            self::Cancelled => 'Cancelled',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Identified => 'info',
            self::Qualification => 'warning',
            self::BidPreparation => 'primary',
            self::Submitted => 'success',
            self::Won => 'success',
            self::Lost => 'danger',
            self::Cancelled => 'danger',
        };
    }
}
