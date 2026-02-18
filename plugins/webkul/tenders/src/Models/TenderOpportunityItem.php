<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderOpportunityItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tender_opportunity_items';

    protected $fillable = [
        'tender_id',
        'item_code',
        'item_name',
        'description',
        'uom',
        'qty',
        'rate',
        'total_cost',
        'notes',
    ];

    protected $casts = [
        'qty'        => 'decimal:4',
        'rate'       => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->total_cost = $model->qty * $model->rate;
        });
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }
}
