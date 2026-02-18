<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Chatter\Traits\HasLogActivity;

class TenderCompetitor extends Model
{
    use HasLogActivity;

    protected $table = 'tender_competitors';

    protected $fillable = [
        'tender_id',
        'competitor_id',
        'competitor_name',
        'likelihood_to_bid',
        'strengths',
        'our_differentiation',
        'bid_price',
        'notes',
        'is_winner',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }

    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class, 'competitor_id');
    }

    public function getModelTitle(): string
    {
        return $this->competitor_name ?? 'Tender Competitor';
    }
}
