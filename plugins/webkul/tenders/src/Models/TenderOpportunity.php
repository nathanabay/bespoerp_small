<?php

namespace Webkul\Tender\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\Security\Models\User;
use Webkul\Partner\Models\Partner;
use Webkul\Chatter\Traits\HasChatter;
use Webkul\Chatter\Traits\HasLogActivity;

class TenderOpportunity extends Model
{
    use HasChatter, HasFactory, HasLogActivity, SoftDeletes;

    protected $table = 'tender_opportunities';

    protected $fillable = [
        'series',
        'title',
        'client_id',
        'status',
        'submission_deadline',
        'value_estimated',
        'description',
        'manager_id',
        'sector',
        'tender_type',
        'bid_probability',
        'approver_id',
        'kick_off_notes',
        'win_themes',
        'narrative_strategy',
        'legal_review_checked',
        'compliance_checklist',
        'bond_type',
        'bond_amount',
        'bond_expiry_date',
        'bid_security_request_link',
        'loss_reason',
        'winning_bid_price',
        'lessons_learned',
        // Phase 1.1: ESG & Social Value
        'esg_impact_score',
        'environmental_initiatives',
        'social_value_commitment',
        'governance_compliance',
        // Phase 1.2: Document Purchase
        'document_price',
        'purchase_date',
        'purchase_receipt_no',
        'doc_purchase_status',
        'tender_purchaser_id',
        'doc_purchase_payment_entry',
        // Phase 1.3: Risk Assessment
        'technical_risk',
        'commercial_risk',
        'financial_risk',
        'scope_creep_risk',
        'resource_availability_risk',
        'reputational_risk',
        'competition_level',
        'customer_relationship',
        // Phase 1.4: Strategic Qualification
        'strategic_alignment_score',
        'historical_win_rate_with_client',
        'opportunity_cost_assessment',
        'incumbent_vendor',
        'decision_notes',
        // Phase 1.7: Critical Dates
        'publication_date',
        'clarification_deadline',
        'site_visit_date',
        'pre_bid_meeting_date',
        'decision_date',
        'tender_number',
        'url',
        // Phase 2.1: Client Intelligence
        'relationship_history',
        'key_decision_makers',
        'past_performance',
        'client_preferences',
        'incumbent_advantage',
        'political_landscape',
        // Phase 2: Capture Plan
        'customer_hot_buttons',
        'key_success_factors',
        'customer_pain_points',
        'solution_overview',
        'value_proposition',
        'executive_summary_themes',
        // Phase 2: Data & Evidence
        'key_data_points',
        'visualisation_required',
        'narrative_strategy_details',
        // Phase 2: Compliance Watchpoints
        'price_lock_in_risk',
        'supply_chain_volatility_risk',
        'contractual_clause_review',
        'payment_terms_negotiated',
        'team_capacity_check',
        // Phase 2: Post-Bid Presentation
        'shortlisted_for_presentation',
        'presentation_date',
        'presentation_leader_id',
        'presentation_feedback',
        // Phase 2: Budget & Costing
        'estimated_cost',
        'margin_percentage',
        'final_bid_price',
        'budget_source',
        'project_duration_months',
        'payment_terms',
        // Phase 2: Tender Files
        'full_tender_document',
        'technical_proposal',
        'financial_proposal_doc',
        'payment_receipt_proof',
        'source_evidence',
        // Phase 3: Outcome & Debrief
        'negotiation_notes',
        'final_contract_value',
        'price_difference',
        'client_feedback_score',
        'main_weakness_identified',
        'debrief_notes',
        // Phase 3: Reporting Metrics
        'revenue_potential',
        'weighted_revenue',
        'forecast_quarter',
        // Phase 3: Compliance Checklist
        'mandatory_requirements_met',
        'compliance_matrix_complete',
        'formatting_check',
        'formatting_check',
        'submission_format_validated',
        // Phase 4: ERP Integration
        'quotation_id',
    ];

    protected $casts = [
        'submission_deadline' => 'datetime',
        'value_estimated'     => 'decimal:2',
        'bid_probability'     => 'decimal:2',
        'legal_review_checked' => 'boolean',
        'compliance_checklist' => 'array',
        'bond_amount'         => 'decimal:2',
        'bond_expiry_date'    => 'date',
        'winning_bid_price'   => 'decimal:2',
        // Phase 1 casts
        'social_value_commitment' => 'decimal:2',
        'governance_compliance' => 'boolean',
        'document_price' => 'decimal:2',
        'purchase_date' => 'date',
        'historical_win_rate_with_client' => 'decimal:2',
        'publication_date' => 'date',
        'clarification_deadline' => 'date',
        'site_visit_date' => 'datetime',
        'pre_bid_meeting_date' => 'datetime',
        'decision_date' => 'date',
        // Phase 2 casts
        'visualisation_required' => 'boolean',
        'price_lock_in_risk' => 'boolean',
        'contractual_clause_review' => 'boolean',
        'payment_terms_negotiated' => 'boolean',
        'team_capacity_check' => 'boolean',
        'shortlisted_for_presentation' => 'boolean',
        'presentation_date' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'margin_percentage' => 'decimal:2',
        'final_bid_price' => 'decimal:2',
        // Phase 3 casts
        'final_contract_value' => 'decimal:2',
        'price_difference' => 'decimal:2',
        'revenue_potential' => 'decimal:2',
        'weighted_revenue' => 'decimal:2',
        'mandatory_requirements_met' => 'boolean',
        'compliance_matrix_complete' => 'boolean',
        'formatting_check' => 'boolean',
        'submission_format_validated' => 'boolean',
        'kick_off_notes' => 'array',
        'customer_pain_points' => 'array',
        'solution_overview' => 'array',
        'value_proposition' => 'array',
        'executive_summary_themes' => 'array',
        'narrative_strategy_details' => 'array',
        'negotiation_notes' => 'array',
        'debrief_notes' => 'array',
        'source_evidence' => 'array',
        'full_tender_document' => 'array',
        'technical_proposal' => 'array',
        'financial_proposal_doc' => 'array',
        'payment_receipt_proof' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if (!$model->series) {
                $model->series = 'T-OPP-' . now()->format('Y') . '-' . str_pad($model->id, 4, '0', STR_PAD_LEFT);
                $model->saveQuietly();
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'client_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function tenderPurchaser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tender_purchaser_id');
    }

    public function presentationLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'presentation_leader_id');
    }

    // Phase 4 Relationships
    public function generatedDocuments(): HasMany
    {
        return $this->hasMany(TenderGeneratedDocument::class, 'tender_id');
    }

    // Assuming Quotation model exists in standard location or generic relationship
    // Since we don't know the exact namespace of Quotation, and it might be optional
    // We can define it but maybe comment out or use loose typing if needed.
    // Use \Webkul\Sales\Models\Order or specific Quotation model if known.
    // For now, let's assume standard Webkul structure or leave it for now until verification.
    // Actually, let's add it if we can find the class.
    
    // public function quotation(): BelongsTo
    // {
    //      return $this->belongsTo(\Webkul\Sales\Models\Order::class, 'quotation_id'); // Example
    // } 
    // I'll skip adding the relationship method for now to avoid class not found error
    // until I verify where Quotation is. The field is there.

    // Phase 2 Relationships

    // Phase 2 Relationships
    public function qualificationCriteria(): HasMany
    {
        return $this->hasMany(QualificationCriterion::class, 'tender_id');
    }

    public function tenderCompetitors(): HasMany
    {
        return $this->hasMany(TenderCompetitor::class, 'tender_id');
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(TenderTeamMember::class, 'tender_id');
    }

    public function insuranceRequirements(): HasMany
    {
        return $this->hasMany(InsuranceRequirement::class, 'tender_id');
    }

    public function rankings(): HasMany
    {
        return $this->hasMany(TenderRanking::class, 'tender_id');
    }

    public function competitors(): HasMany
    {
        return $this->hasMany(TenderCompetitor::class, 'tender_id');
    }

    public function proposalSections(): HasMany
    {
        return $this->hasMany(ProposalSection::class);
    }

    public function bidDecisionFactors(): HasMany
    {
        return $this->hasMany(BidDecisionFactor::class, 'tender_id');
    }

    public function clarificationQuestions(): HasMany
    {
        return $this->hasMany(ClarificationQuestion::class, 'tender_id');
    }

    public function bidSecurityRequests(): HasMany
    {
        return $this->hasMany(BidSecurityRequest::class, 'tender_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TenderOpportunityItem::class, 'tender_id');
    }

    public function getModelTitle(): string
    {
        return $this->title;
    }

    public function getLogAttributeLabels(): array
    {
        return [
            'series'                => 'Series',
            'title'                 => 'Title',
            'status'                => 'Status',
            'client_id'             => 'Client',
            'value_estimated'       => 'Estimated Value',
            'submission_deadline'   => 'Submission Deadline',
            'manager_id'            => 'Manager',
            'sector'                => 'Sector',
            'tender_type'           => 'Type',
            'bid_probability'       => 'Bid Probability',
            'approver_id'           => 'Approver',
            'legal_review_checked'  => 'Legal Review Checked',
            'bond_type'             => 'Bond Type',
            'bond_amount'           => 'Bond Amount',
            'bond_expiry_date'      => 'Bond Expiry Date',
            'loss_reason'           => 'Loss Reason',
            'winning_bid_price'     => 'Winning Price',
        ];
    }

    public function getGoNoGoScoreAttribute(): float
    {
        $totalScore = $this->rankings->sum(function ($ranking) {
            return $ranking->score * $ranking->weight;
        });

        return round($totalScore, 2);
    }

    public function getRecommendationAttribute(): string
    {
        $score = $this->go_no_go_score;

        if ($score >= 50) {
            return 'Bid (Go)';
        }

        return 'No-Go';
    }
}
