<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_competitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tender_opportunities')->onDelete('cascade');
            $table->foreignId('competitor_id')->nullable()->constrained('competitors')->onDelete('set null');
            $table->string('competitor_name')->comment('Name if not in master');
            $table->enum('likelihood_to_bid', ['Low', 'Medium', 'High', 'Confirmed'])->default('Medium');
            $table->text('strengths')->nullable()->comment('Their competitive advantages for this tender');
            $table->text('our_differentiation')->nullable()->comment('How we differentiate against them');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_competitors');
    }
};
