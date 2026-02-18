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
        Schema::create('tender_contract_milestones', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('performance_bond_id')->constrained('tender_performance_bonds')->cascadeOnDelete();
            $blueprint->string('description');
            $blueprint->date('due_date')->nullable();
            $blueprint->decimal('amount', 15, 2)->default(0);
            $blueprint->string('status')->default('pending');
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });

        Schema::create('tender_bid_decision_factors', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('tender_id')->constrained('tender_opportunities')->cascadeOnDelete();
            $blueprint->string('factor');
            $blueprint->decimal('weight', 5, 2)->default(1.0);
            $blueprint->integer('score')->default(0);
            $blueprint->text('comments')->nullable();
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });

        Schema::create('tender_clarification_questions', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('tender_id')->constrained('tender_opportunities')->cascadeOnDelete();
            $blueprint->text('question');
            $blueprint->text('answer')->nullable();
            $blueprint->string('status')->default('pending');
            $blueprint->foreignId('assigned_to_id')->nullable()->constrained('users');
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_clarification_questions');
        Schema::dropIfExists('tender_bid_decision_factors');
        Schema::dropIfExists('tender_contract_milestones');
    }
};
