<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Security\Models\User;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;

class TenderTask extends Model
{
    use HasChatter, HasFactory, HasLogActivity, SoftDeletes;

    protected $table = 'tender_tasks';

    protected $fillable = [
        'series',
        'title',
        'description',
        'due_date',
        'status',
        'assigned_to_id',
        'tender_id',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if (!$model->series) {
                $tenderSeries = $model->tender?->series ?? 'TNDR';
                $model->series = 'TASK-' . $tenderSeries . '-' . str_pad($model->id, 4, '0', STR_PAD_LEFT);
                $model->saveQuietly();
            }
        });
    }

    public function getModelTitle(): string
    {
        return $this->title;
    }

    public function getLogAttributeLabels(): array
    {
        return [
            'series'         => 'Series',
            'title'          => 'Title',
            'status'         => 'Status',
            'assigned_to_id' => 'Assigned To',
            'due_date'       => 'Due Date',
            'tender_id'      => 'Tender',
        ];
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(TenderOpportunity::class, 'tender_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
}
