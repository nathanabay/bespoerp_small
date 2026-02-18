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
            // Risk Assessment Framework
            $table->string('technical_risk')->nullable(); // Low/Medium/High
            $table->string('commercial_risk')->nullable(); // Low/Medium/High
            $table->string('financial_risk')->nullable(); // Low/Medium/High
            $table->string('scope_creep_risk')->default('Low'); // Low/Medium/High
            $table->string('resource_availability_risk')->default('Low'); // Low/Medium/High
            $table->string('reputational_risk')->default('Low'); // Low/Medium/High
            $table->string('competition_level')->nullable(); // Low/Moderate/Intense/Locked Spec
            $table->string('customer_relationship')->nullable(); // New Client/Existing - Good/Existing - Strained/Blacklisted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'technical_risk',
                'commercial_risk',
                'financial_risk',
                'scope_creep_risk',
                'resource_availability_risk',
                'reputational_risk',
                'competition_level',
                'customer_relationship',
            ]);
        });
    }
};
