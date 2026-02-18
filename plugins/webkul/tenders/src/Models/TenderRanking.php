<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenderRanking extends Model
{
    use HasFactory;

    protected $table = 'tender_rankings';

    protected $fillable = [
        'tender_id',
        'criterion',
        'score',
        'weight',
        'notes',
    ];

    protected $casts = [
        'score' => 'integer',
        'weight' => 'decimal:2',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }
}
