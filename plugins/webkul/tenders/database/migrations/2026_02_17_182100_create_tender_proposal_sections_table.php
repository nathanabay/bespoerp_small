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
        Schema::create('tender_proposal_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_opportunity_id')->constrained('tender_opportunities')->onDelete('cascade');
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('status')->default('draft');
            $table->foreignId('assigned_to_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_proposal_sections');
    }
};
