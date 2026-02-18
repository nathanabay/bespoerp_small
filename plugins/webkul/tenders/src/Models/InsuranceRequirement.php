<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Chatter\Traits\HasLogActivity;

class InsuranceRequirement extends Model
{
    use HasLogActivity;

    protected $table = 'insurance_requirements';

    protected $fillable = [
        'tender_id',
        'insurance_type',
        'coverage_amount',
        'specific_requirements',
        'coverage_confirmed',
        'insurer_details',
    ];

    protected $casts = [
        'coverage_amount' => 'decimal:2',
        'coverage_confirmed' => 'boolean',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }

    public function getModelTitle(): string
    {
        return $this->insurance_type ?? 'Insurance Requirement';
    }
}
