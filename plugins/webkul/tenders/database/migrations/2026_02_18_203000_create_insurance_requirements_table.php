<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tender_opportunities')->onDelete('cascade');
            $table->enum('insurance_type', ['Professional Indemnity', 'Public Liability', 'Product Liability', 'Employers Liability', 'Performance Bond', 'Other'])->default('Other');
            $table->decimal('coverage_amount', 15, 2);
            $table->text('specific_requirements')->nullable();
            $table->boolean('coverage_confirmed')->default(false);
            $table->text('insurer_details')->nullable()->comment('Insurance provider name and policy info');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_requirements');
    }
};
