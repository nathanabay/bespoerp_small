<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;

class BidDecisionMatrix extends Model
{
    use HasChatter, HasFactory, HasLogActivity, SoftDeletes;

    protected $table = 'tender_bid_decision_matrices';

    protected $fillable = [
        'series',
        'tender_id',
        'win_probability',
        'profitability',
        'strategic_fit',
        'resource_availability',
        'technical_capability',
        'client_relationship',
        'total_score',
        'suggested_decision',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if (!$model->series) {
                $tenderSeries = $model->tender?->series ?? 'TNDR';
                $model->series = 'BDM-' . $tenderSeries . '-' . str_pad($model->id, 4, '0', STR_PAD_LEFT);
                $model->saveQuietly();
            }
        });

        static::saving(function ($model) {
            $model->total_score = $model->win_probability +
                $model->profitability +
                $model->strategic_fit +
                $model->resource_availability +
                $model->technical_capability +
                $model->client_relationship;

            if ($model->total_score >= 45) {
                $model->suggested_decision = 'Bid (High Priority)';
            } elseif ($model->total_score >= 30) {
                $model->suggested_decision = 'Bid (Medium Priority)';
            } else {
                $model->suggested_decision = 'No-Go';
            }
        });
    }

    public function getModelTitle(): string
    {
        return $this->series ?? 'Decision Matrix';
    }

    public function getLogAttributeLabels(): array
    {
        return [
            'series'                 => 'Series',
            'tender_id'              => 'Tender',
            'win_probability'        => 'Win Probability',
            'profitability'          => 'Profitability',
            'strategic_fit'          => 'Strategic Fit',
            'resource_availability'  => 'Resource Availability',
            'technical_capability'   => 'Technical Capability',
            'client_relationship'    => 'Client Relationship',
            'total_score'            => 'Total Score',
            'suggested_decision'     => 'Suggested Decision',
        ];
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }
}
