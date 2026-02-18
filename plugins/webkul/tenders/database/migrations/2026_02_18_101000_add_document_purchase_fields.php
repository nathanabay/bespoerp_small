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
        Schema::table('tender_opportunities', function (Blueprint $table) {
            // Document Purchase Workflow
            $table->decimal('document_price', 15, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('purchase_receipt_no')->nullable();
            $table->string('doc_purchase_status')->default('Pending Assignment');
            $table->foreignId('tender_purchaser_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('doc_purchase_payment_entry')->nullable(); // Link to Payment Entry
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            $table->dropForeign(['tender_purchaser_id']);
            $table->dropColumn([
                'document_price',
                'purchase_date',
                'purchase_receipt_no',
                'doc_purchase_status',
                'tender_purchaser_id',
                'doc_purchase_payment_entry',
            ]);
        });
    }
};
