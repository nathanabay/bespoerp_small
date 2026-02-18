<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BidDecisionFactor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tender_bid_decision_factors';

    protected $fillable = [
        'tender_id',
        'factor',
        'weight',
        'score',
        'comments',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'score'  => 'integer',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }
}
