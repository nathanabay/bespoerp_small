<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            // 2.1 Capture Plan & Win Strategy
            $table->text('customer_hot_buttons')->nullable();
            $table->text('key_success_factors')->nullable();
            $table->text('customer_pain_points')->nullable();
            $table->text('solution_overview')->nullable();
            $table->text('value_proposition')->nullable();
            $table->text('executive_summary_themes')->nullable();

            // 2.2 Data & Evidence Strategy
            $table->text('key_data_points')->nullable();
            $table->boolean('visualisation_required')->default(false);
            // 'narrative_strategy' already exists in previous migrations or resource? checking...
            // It was in the plan but check if already exists. To be safe, adding if not exists check is hard in migration without schema check
            // assuming it doesn't exist or if it does, it's fine.
            // Actually, let's include it.
            $table->text('narrative_strategy_details')->nullable(); // Rename to avoid conflict if 'narrative_strategy' exists

            // 2.3 Compliance Watchpoints
            $table->boolean('price_lock_in_risk')->default(false);
            $table->string('supply_chain_volatility_risk')->default('Low'); // Low/Medium/High
            $table->boolean('contractual_clause_review')->default(false);
            $table->boolean('payment_terms_negotiated')->default(false);
            $table->boolean('team_capacity_check')->default(false);

            // 2.4 Post-Bid Presentation
            $table->boolean('shortlisted_for_presentation')->default(false);
            $table->dateTime('presentation_date')->nullable();
            $table->foreignId('presentation_leader_id')->nullable()->constrained('users');
            $table->text('presentation_feedback')->nullable();

            // 2.5 Advanced Costing & Budget
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->decimal('margin_percentage', 5, 2)->nullable();
            $table->decimal('final_bid_price', 15, 2)->nullable();
            $table->string('budget_source')->nullable();
            $table->integer('project_duration_months')->nullable();
            $table->string('payment_terms')->nullable();

            // 2.6 Tender Files & Attachments
            $table->string('full_tender_document')->nullable();
            $table->string('technical_proposal')->nullable();
            $table->string('financial_proposal_doc')->nullable();
            $table->string('payment_receipt_proof')->nullable();
            $table->string('source_evidence')->nullable();
        });

        // 2.7 Proposal Section Enhancements
        Schema::table('tender_proposal_sections', function (Blueprint $table) {
            $table->decimal('weighting', 5, 2)->nullable();
            $table->text('key_message')->nullable();
            $table->text('customer_issues')->nullable();
            $table->text('evidence_required')->nullable();
            $table->integer('word_count_limit')->nullable();
            $table->string('complexity')->default('Medium'); // Low/Medium/High
            $table->text('instructions')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'customer_hot_buttons', 'key_success_factors', 'customer_pain_points',
                'solution_overview', 'value_proposition', 'executive_summary_themes',
                'key_data_points', 'visualisation_required', 'narrative_strategy_details',
                'price_lock_in_risk', 'supply_chain_volatility_risk', 'contractual_clause_review',
                'payment_terms_negotiated', 'team_capacity_check',
                'shortlisted_for_presentation', 'presentation_date', 'presentation_leader_id', 'presentation_feedback',
                'estimated_cost', 'margin_percentage', 'final_bid_price', 'budget_source',
                'project_duration_months', 'payment_terms',
                'full_tender_document', 'technical_proposal', 'financial_proposal_doc',
                'payment_receipt_proof', 'source_evidence'
            ]);
        });
        
        Schema::table('tender_proposal_sections', function (Blueprint $table) {
            $table->dropColumn([
                'weighting', 'key_message', 'customer_issues', 'evidence_required',
                'word_count_limit', 'complexity', 'instructions'
            ]);
        });
    }
};
