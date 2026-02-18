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
        Schema::create('tender_cost_estimations', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('series')->nullable()->unique();
            $blueprint->foreignId('tender_id')->constrained('tender_opportunities')->cascadeOnDelete();
            
            $blueprint->decimal('total_direct_cost', 15, 2)->default(0);
            $blueprint->decimal('overhead_percentage', 5, 2)->default(15.00);
            $blueprint->decimal('profit_margin_percentage', 5, 2)->default(10.00);
            $blueprint->decimal('total_price', 15, 2)->default(0);
            
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });

        Schema::create('tender_cost_items', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('cost_estimation_id')->constrained('tender_cost_estimations')->cascadeOnDelete();
            $blueprint->string('description');
            $blueprint->decimal('quantity', 15, 2)->default(1);
            $blueprint->string('uom')->nullable();
            $blueprint->decimal('unit_cost', 15, 2)->default(0);
            $blueprint->decimal('total_cost', 15, 2)->default(0);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_cost_items');
        Schema::dropIfExists('tender_cost_estimations');
    }
};
