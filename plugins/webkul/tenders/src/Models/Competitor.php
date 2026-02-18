<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competitor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tender_competitors_master';

    protected $fillable = [
        'competitor_name',
        'company_type',
        'sector',
        'contact_person',
        'phone',
        'email',
        'website',
        'strengths',
        'weaknesses',
        'typical_pricing_strategy',
        'notes',
    ];

    public function getModelTitle(): string
    {
        return $this->competitor_name;
    }
}
