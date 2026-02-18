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
        Schema::create('tender_bid_security_requests', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('tender_id')->constrained('tender_opportunities')->cascadeOnDelete();
            $blueprint->string('series')->nullable();
            $blueprint->string('status')->default('draft');
            $blueprint->decimal('amount', 15, 2);
            $blueprint->foreignId('bank_account_id')->nullable()->constrained('accounting_bank_accounts');
            $blueprint->foreignId('prepared_by_id')->nullable()->constrained('users');
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });

        Schema::create('tender_document_templates', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('template_name')->unique();
            $blueprint->string('category');
            $blueprint->longText('content');
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });

        Schema::create('tender_content_library', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('title');
            $blueprint->string('category');
            $blueprint->longText('content')->nullable();
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });

        Schema::create('tender_opportunity_items', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('tender_id')->constrained('tender_opportunities')->cascadeOnDelete();
            $blueprint->text('description');
            $blueprint->decimal('qty', 15, 4)->default(1);
            $blueprint->decimal('rate', 15, 2)->default(0);
            $blueprint->decimal('total_cost', 15, 2)->default(0);
            $blueprint->timestamps();
            $blueprint->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_opportunity_items');
        Schema::dropIfExists('tender_content_library');
        Schema::dropIfExists('tender_document_templates');
        Schema::dropIfExists('tender_bid_security_requests');
    }
};
