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
        Schema::create('tender_bid_decision_matrices', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('series')->nullable()->unique();
            $blueprint->foreignId('tender_id')->constrained('tender_opportunities')->cascadeOnDelete();
            
            $blueprint->integer('win_probability')->default(0);
            $blueprint->integer('profitability')->default(0);
            $blueprint->integer('strategic_fit')->default(0);
            $blueprint->integer('resource_availability')->default(0);
            $blueprint->integer('technical_capability')->default(0);
            $blueprint->integer('client_relationship')->default(0);
            
            $blueprint->integer('total_score')->default(0);
            $blueprint->string('suggested_decision')->nullable();
            
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_bid_decision_matrices');
    }
};
