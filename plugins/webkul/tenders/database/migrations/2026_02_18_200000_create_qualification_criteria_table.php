<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qualification_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tender_opportunities')->onDelete('cascade');
            $table->string('criterion')->comment('Qualification requirement name');
            $table->enum('type', ['Mandatory', 'Desirable', 'Scored'])->default('Mandatory');
            $table->boolean('met')->default(false)->comment('Whether we meet this criterion');
            $table->text('evidence')->nullable()->comment('Supporting evidence/documentation');
            $table->text('gap_mitigation_plan')->nullable()->comment('Plan if criterion not fully met');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qualification_criteria');
    }
};
