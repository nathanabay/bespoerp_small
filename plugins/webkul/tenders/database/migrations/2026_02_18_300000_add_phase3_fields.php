<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            // 3.1 Outcome & Debrief
            $table->text('negotiation_notes')->nullable();
            $table->decimal('final_contract_value', 15, 2)->nullable();
            $table->decimal('price_difference', 15, 2)->nullable();
            $table->integer('client_feedback_score')->nullable(); // 1-10
            $table->text('main_weakness_identified')->nullable();
            $table->text('debrief_notes')->nullable();

            // 3.2 Reporting Metrics
            $table->decimal('revenue_potential', 15, 2)->nullable();
            $table->decimal('weighted_revenue', 15, 2)->nullable(); // Auto: revenue_potential * bid_probability
            $table->string('forecast_quarter')->nullable(); // Q1/Q2/Q3/Q4

            // 3.3 Compliance Submission Checklist (Booleans)
            $table->boolean('mandatory_requirements_met')->default(false);
            $table->boolean('compliance_matrix_complete')->default(false);
            $table->boolean('formatting_check')->default(false);
            $table->boolean('submission_format_validated')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'negotiation_notes', 'final_contract_value', 'price_difference',
                'client_feedback_score', 'main_weakness_identified', 'debrief_notes',
                'revenue_potential', 'weighted_revenue', 'forecast_quarter',
                'mandatory_requirements_met', 'compliance_matrix_complete',
                'formatting_check', 'submission_format_validated'
            ]);
        });
    }
};
