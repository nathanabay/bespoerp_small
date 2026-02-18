<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CostItem extends Model
{
    protected $table = 'tender_cost_items';

    protected $fillable = [
        'cost_estimation_id',
        'description',
        'quantity',
        'uom',
        'unit_cost',
        'total_cost',
    ];

    protected $casts = [
        'quantity'   => 'decimal:2',
        'unit_cost'  => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->total_cost = $model->quantity * $model->unit_cost;
        });

        static::saved(function ($model) {
            $model->costEstimation->calculateTotals();
        });

        static::deleted(function ($model) {
            $model->costEstimation->calculateTotals();
        });
    }

    public function costEstimation(): BelongsTo
    {
        return $this->belongsTo(CostEstimation::class, 'cost_estimation_id');
    }
}
