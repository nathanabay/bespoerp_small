<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Security\Models\User;
use Webkul\Accounting\Models\BankAccount;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;

class BidSecurityRequest extends Model
{
    use HasChatter, HasFactory, HasLogActivity, SoftDeletes;

    protected $table = 'tender_bid_security_requests';

    protected $fillable = [
        'tender_id',
        'series',
        'status',
        'amount',
        'bank_account_id',
        'prepared_by_id',
        // Phase 1.6: Enhanced Bid Security Request
        'organization',
        'type',
        'validity_period_days',
        'required_date',
        'security_number',
        'expiry_date',
        'journal_entry',
        'checked_by_id',
        'approved_by_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'required_date' => 'date',
        'expiry_date' =>'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if (!$model->series) {
                $model->series = 'BSR-' . now()->format('Y') . '-' . str_pad($model->id, 4, '0', STR_PAD_LEFT);
                $model->saveQuietly();
            }
        });
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by_id');
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function getModelTitle(): string
    {
        return $this->series ?: 'Bid Security Request';
    }
}
