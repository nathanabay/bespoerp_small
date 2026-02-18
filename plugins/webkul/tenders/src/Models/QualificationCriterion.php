<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Chatter\Traits\HasLogActivity;

class QualificationCriterion extends Model
{
    use HasLogActivity;

    protected $table = 'qualification_criteria';

    protected $fillable = [
        'tender_id',
        'criterion',
        'type',
        'met',
        'evidence',
        'gap_mitigation_plan',
    ];

    protected $casts = [
        'met' => 'boolean',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }

    public function getModelTitle(): string
    {
        return $this->criterion ?? 'Qualification Criterion';
    }
}
