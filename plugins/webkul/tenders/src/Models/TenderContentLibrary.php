<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Security\Models\User;

class TenderContentLibrary extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tender_content_library';

    protected $fillable = [
        'title',
        'category',
        'content',
        'status',
        'content_owner_id',
        'keywords',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function contentOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'content_owner_id');
    }
}
