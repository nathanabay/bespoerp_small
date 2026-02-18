<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Accounting\Models\JournalEntry;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;

class PerformanceBond extends Model
{
    use HasChatter, HasFactory, HasLogActivity, SoftDeletes;

    protected $table = 'tender_performance_bonds';

    protected $fillable = [
        'series',
        'tender_id',
        'bond_number',
        'bond_type',
        'amount',
        'bank_name',
        'expiry_date',
        'journal_entry_id',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'expiry_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if (!$model->series) {
                $tenderSeries = $model->tender?->series ?? 'TNDR';
                $model->series = 'PB-' . $tenderSeries . '-' . str_pad($model->id, 4, '0', STR_PAD_LEFT);
                $model->saveQuietly();
            }
        });
    }

    public function getModelTitle(): string
    {
        return $this->bond_number . ' (' . $this->bank_name . ')';
    }

    public function getLogAttributeLabels(): array
    {
        return [
            'series'           => 'Series',
            'tender_id'        => 'Tender',
            'bond_number'      => 'Bond Number',
            'bond_type'        => 'Bond Type',
            'amount'           => 'Amount',
            'bank_name'        => 'Bank Name',
            'expiry_date'      => 'Expiry Date',
            'journal_entry_id' => 'Journal Entry',
        ];
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function contractMilestones(): HasMany
    {
        return $this->hasMany(ContractMilestone::class, 'performance_bond_id');
    }
}
