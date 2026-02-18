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
        Schema::create('tender_performance_bonds', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('series')->nullable()->unique();
            $blueprint->foreignId('tender_id')->constrained('tender_opportunities')->cascadeOnDelete();
            
            $blueprint->string('bond_number');
            $blueprint->string('bond_type');
            $blueprint->decimal('amount', 15, 2);
            $blueprint->string('bank_name');
            $blueprint->date('expiry_date');
            
            $blueprint->foreignId('journal_entry_id')->nullable()->constrained('accounting_journal_entries')->nullOnDelete();
            
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_performance_bonds');
    }
};
