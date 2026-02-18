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
        Schema::create('tender_competitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tender_opportunities')->cascadeOnDelete();
            $table->foreignId('competitor_id')->constrained('partners_partners')->cascadeOnDelete();
            $table->text('strength')->nullable();
            $table->text('weakness')->nullable();
            $table->decimal('price_offered', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_competitors');
    }
};
