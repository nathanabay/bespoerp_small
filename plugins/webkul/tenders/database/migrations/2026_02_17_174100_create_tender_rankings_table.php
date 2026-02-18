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
        Schema::create('tender_rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tender_opportunities')->cascadeOnDelete();
            $table->string('criterion');
            $table->integer('score')->default(0);
            $table->decimal('weight', 5, 2)->default(1.0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_rankings');
    }
};
