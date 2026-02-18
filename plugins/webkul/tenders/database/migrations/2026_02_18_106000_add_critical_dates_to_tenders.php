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
            // Critical Dates Tracking
            $table->date('publication_date')->nullable();
            $table->date('clarification_deadline')->nullable();
            $table->dateTime('site_visit_date')->nullable();
            $table->dateTime('pre_bid_meeting_date')->nullable();
            $table->date('decision_date')->nullable();
            
            // External Reference & Source
            $table->string('tender_number')->nullable(); // External tender reference
            $table->string('url')->nullable(); // Tender source link
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'publication_date',
                'clarification_deadline',
                'site_visit_date',
                'pre_bid_meeting_date',
                'decision_date',
                'tender_number',
                'url',
            ]);
        });
    }
};
