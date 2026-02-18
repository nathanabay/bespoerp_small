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
            // ESG & Social Value Framework
            $table->string('esg_impact_score')->nullable()->after('tender_type'); // Low/Medium/High
            $table->text('environmental_initiatives')->nullable();
            $table->decimal('social_value_commitment', 15, 2)->nullable();
            $table->boolean('governance_compliance')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'esg_impact_score',
                'environmental_initiatives',
                'social_value_commitment',
                'governance_compliance',
            ]);
        });
    }
};
