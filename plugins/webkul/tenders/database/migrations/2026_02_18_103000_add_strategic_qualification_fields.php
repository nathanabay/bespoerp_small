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
            // Strategic Qualification
            $table->string('strategic_alignment_score')->default('Medium'); // Low/Medium/High
            $table->decimal('historical_win_rate_with_client', 5, 2)->nullable();
            $table->text('opportunity_cost_assessment')->nullable();
            $table->string('incumbent_vendor')->nullable();
            $table->text('decision_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'strategic_alignment_score',
                'historical_win_rate_with_client',
                'opportunity_cost_assessment',
                'incumbent_vendor',
                'decision_notes',
            ]);
        });
    }
};
