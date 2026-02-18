<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Security\Models\User;

use Webkul\Chatter\Traits\HasLogActivity;

class ProposalSection extends Model
{
    use HasLogActivity;

    protected $table = 'tender_proposal_sections';

    public function getModelTitle(): string
    {
        return $this->title;
    }

    public function getLogAttributeLabels(): array
    {
        return [
            'title'          => 'Title',
            'status'         => 'Status',
            'assigned_to_id' => 'Assigned To',
        ];
    }

    protected $fillable = [
        'tender_opportunity_id',
        'title',
        'content',
        'status',
        'assigned_to_id',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function tenderOpportunity(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
}
