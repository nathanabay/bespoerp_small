<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Security\Models\User;

class ClarificationQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tender_clarification_questions';

    protected $fillable = [
        'tender_id',
        'question',
        'answer',
        'date_asked',
        'status',
        'assigned_to_id',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
}
