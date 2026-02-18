<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractMilestone extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tender_contract_milestones';

    protected $fillable = [
        'performance_bond_id',
        'description',
        'due_date',
        'amount',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount'   => 'decimal:2',
    ];

    public function performanceBond(): BelongsTo
    {
        return $this->belongsTo(PerformanceBond::class, 'performance_bond_id');
    }
}
