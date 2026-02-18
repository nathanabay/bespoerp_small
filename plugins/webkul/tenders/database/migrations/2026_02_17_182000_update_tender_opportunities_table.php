<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            // Identify
            $table->string('series')->nullable()->after('id');
            $table->string('sector')->nullable();
            $table->string('tender_type')->nullable();
            
            // Qualify
            $table->decimal('bid_probability', 5, 2)->nullable();
            $table->foreignId('approver_id')->nullable()->constrained('users');
            
            // Plan
            $table->text('kick_off_notes')->nullable();
            $table->text('win_themes')->nullable();
            $table->text('narrative_strategy')->nullable();
            $table->boolean('legal_review_checked')->default(false);
            
            // Proposal
            $table->json('compliance_checklist')->nullable();
            
            // Bid Bond
            $table->string('bond_type')->nullable();
            $table->decimal('bond_amount', 15, 2)->nullable();
            $table->date('bond_expiry_date')->nullable();
            $table->string('bid_security_request_link')->nullable();
            
            // Outcome
            $table->string('loss_reason')->nullable();
            $table->decimal('winning_bid_price', 15, 2)->nullable();
            $table->text('lessons_learned')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'series', 'sector', 'tender_type', 'bid_probability', 'approver_id',
                'kick_off_notes', 'win_themes', 'narrative_strategy', 'legal_review_checked',
                'compliance_checklist', 'bond_type', 'bond_amount', 'bond_expiry_date',
                'bid_security_request_link', 'loss_reason', 'winning_bid_price', 'lessons_learned'
            ]);
        });
    }
};
