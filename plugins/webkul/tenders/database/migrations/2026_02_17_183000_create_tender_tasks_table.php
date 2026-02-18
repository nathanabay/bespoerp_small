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
        Schema::create('tender_tasks', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('series')->nullable()->unique();
            $blueprint->string('title');
            $blueprint->text('description')->nullable();
            $blueprint->date('due_date')->nullable();
            $blueprint->string('status')->default('open');
            
            $blueprint->foreignId('assigned_to_id')->nullable()->constrained('users')->nullOnDelete();
            $blueprint->foreignId('tender_id')->constrained('tender_opportunities')->cascadeOnDelete();
            
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_tasks');
    }
};
