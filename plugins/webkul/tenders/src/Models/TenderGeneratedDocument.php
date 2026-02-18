<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Security\Models\User;

class TenderGeneratedDocument extends Model
{
    use HasFactory;

    protected $table = 'tender_generated_documents';

    protected $fillable = [
        'tender_id',
        'template_id',
        'file',
        'generated_by_id',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(DocumentTemplate::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by_id');
    }
}
