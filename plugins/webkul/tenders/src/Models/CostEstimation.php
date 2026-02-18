<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;

class CostEstimation extends Model
{
    use HasChatter, HasFactory, HasLogActivity, SoftDeletes;

    protected $table = 'tender_cost_estimations';

    protected $fillable = [
        'series',
        'tender_id',
        'total_direct_cost',
        'overhead_percentage',
        'profit_margin_percentage',
        'total_price',
    ];

    protected $casts = [
        'total_direct_cost'        => 'decimal:2',
        'overhead_percentage'      => 'decimal:2',
        'profit_margin_percentage' => 'decimal:2',
        'total_price'               => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if (!$model->series) {
                $tenderSeries = $model->tender?->series ?? 'TNDR';
                $model->series = 'CE-' . $tenderSeries . '-' . str_pad($model->id, 4, '0', STR_PAD_LEFT);
                $model->saveQuietly();
            }
        });
    }

    public function getModelTitle(): string
    {
        return $this->series ?? 'Cost Estimation';
    }

    public function getLogAttributeLabels(): array
    {
        return [
            'series'                   => 'Series',
            'tender_id'                => 'Tender',
            'total_direct_cost'        => 'Total Direct Cost',
            'overhead_percentage'      => 'Overhead (%)',
            'profit_margin_percentage' => 'Profit Margin (%)',
            'total_price'               => 'Total Price',
        ];
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(CostItem::class, 'cost_estimation_id');
    }

    public function calculateTotals(): void
    {
        $this->total_direct_cost = $this->items()->sum('total_cost');
        
        $overheadAmount = $this->total_direct_cost * ($this->overhead_percentage / 100);
        $profitAmount = ($this->total_direct_cost + $overheadAmount) * ($this->profit_margin_percentage / 100);
        
        $this->total_price = $this->total_direct_cost + $overheadAmount + $profitAmount;
        $this->saveQuietly();
    }
}
