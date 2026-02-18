<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Security\Models\User;

use Webkul\Chatter\Traits\HasLogActivity;

class TenderTeamMember extends Model
{
    use HasLogActivity;

    protected $table = 'tender_team_members';

    public function getModelTitle(): string
    {
        return 'Team Member';
    }

    public function getLogAttributeLabels(): array
    {
        return [
            'user_id' => 'User',
            'role'    => 'Role',
        ];
    }

    protected $fillable = [
        'tender_id',
        'user_id',
        'role',
        'assigned_date',
        'responsibilities',
    ];

    public function tenderOpportunity(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
