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
        Schema::table('tender_bid_security_requests', function (Blueprint $table) {
            // Enhanced Bid Security Request workflow
            $table->string('organization')->nullable()->after('tender_id');
            $table->string('type')->default('CPO')->after('status'); // CPO/Bank Guarantee/Insurance Bond/Letter of Credit
            $table->integer('validity_period_days')->default(90)->after('amount');
            $table->date('required_date')->nullable()->after('validity_period_days');
            $table->string('security_number')->nullable()->after('required_date');
            $table->date('expiry_date')->nullable()->after('security_number');
            $table->string('journal_entry')->nullable()->after('expiry_date'); // Link to Journal Entry
            $table->foreignId('checked_by_id')->nullable()->after('prepared_by_id')->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by_id')->nullable()->after('checked_by_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tender_bid_security_requests', function (Blueprint $table) {
            $table->dropForeign(['checked_by_id']);
            $table->dropForeign(['approved_by_id']);
            $table->dropColumn([
                'organization',
                'type',
                'validity_period_days',
                'required_date',
                'security_number',
                'expiry_date',
                'journal_entry',
                'checked_by_id',
                'approved_by_id',
            ]);
        });
    }
};
