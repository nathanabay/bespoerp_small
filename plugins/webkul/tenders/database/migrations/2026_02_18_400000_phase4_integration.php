<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 4.1 Child Table Completions
        
        if (Schema::hasTable('tender_opportunity_items')) {
            Schema::table('tender_opportunity_items', function (Blueprint $table) {
                if (!Schema::hasColumn('tender_opportunity_items', 'item_code')) {
                    $table->string('item_code')->nullable();
                    $table->string('item_name')->nullable();
                    $table->string('uom')->nullable();
                    $table->text('notes')->nullable();
                }
            });
        }

        if (Schema::hasTable('tender_team_members')) {
            Schema::table('tender_team_members', function (Blueprint $table) {
                 if (!Schema::hasColumn('tender_team_members', 'assigned_date')) {
                    $table->date('assigned_date')->default(now());
                 }
                 if (!Schema::hasColumn('tender_team_members', 'responsibilities')) {
                    $table->text('responsibilities')->nullable();
                 }
            });
        }

        if (Schema::hasTable('tender_clarification_questions')) {
            Schema::table('tender_clarification_questions', function (Blueprint $table) {
                if (!Schema::hasColumn('tender_clarification_questions', 'date_asked')) {
                    $table->date('date_asked')->default(now());
                }
            });
        }

        if (Schema::hasTable('tender_contract_milestones')) {
             Schema::table('tender_contract_milestones', function (Blueprint $table) {
                 if (!Schema::hasColumn('tender_contract_milestones', 'milestone_name')) {
                    $table->string('milestone_name')->nullable();
                    $table->date('completion_date')->nullable();
                    $table->decimal('payment_percentage', 5, 2)->nullable();
                    $table->boolean('invoice_raised')->default(false);
                    $table->boolean('payment_received')->default(false);
                 }
             });
        }

        if (Schema::hasTable('tender_competitors')) {
            Schema::table('tender_competitors', function (Blueprint $table) {
                if (!Schema::hasColumn('tender_competitors', 'competitor_name')) {
                    $table->string('competitor_name')->nullable();
                    $table->decimal('bid_price', 15, 2)->nullable();
                    $table->text('notes')->nullable();
                    $table->boolean('is_winner')->default(false);
                }
            });
        }

        // 4.2 Content Library Metadata
        if (Schema::hasTable('tender_content_library')) {
            Schema::table('tender_content_library', function (Blueprint $table) {
                $table->string('status')->default('Draft');
                $table->foreignId('content_owner_id')->nullable()->constrained('users');
                $table->date('last_updated')->nullable(); // Default now() handled in code or DB
                $table->text('keywords')->nullable();
            });
        }

        // 4.3 Document Template Polish
        if (Schema::hasTable('tender_document_templates')) {
            Schema::table('tender_document_templates', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
                $table->text('placeholders_help')->nullable();
                $table->text('usage_notes')->nullable();
            });
        }

        // 4.4 ERP Integration Fields
        Schema::table('tender_opportunities', function (Blueprint $table) {
            $table->unsignedBigInteger('quotation_id')->nullable();
            // $table->foreign('quotation_id')->references('id')->on('quotations')->nullOnDelete(); 
            // Commented out constraint to avoid dependency issues if Sales module missing
        });

        if (Schema::hasTable('performance_bonds')) {
            Schema::table('performance_bonds', function (Blueprint $table) {
                $table->string('journal_entry')->nullable();
                $table->string('release_journal_entry')->nullable();
            });
        }

        // 4.5 Generated Documents Table
        if (!Schema::hasTable('tender_generated_documents')) {
            Schema::create('tender_generated_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tender_id')->constrained('tender_opportunities')->cascadeOnDelete();
                $table->foreignId('template_id')->nullable()->constrained('tender_document_templates')->nullOnDelete();
                $table->string('file')->nullable();
                $table->foreignId('generated_by_id')->nullable()->constrained('users');
                $table->dateTime('generated_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_generated_documents');

        Schema::table('tender_opportunities', function (Blueprint $table) {
            $table->dropColumn('quotation_id');
        });

        // Drop columns from other tables if needed, omitted for brevity in Phase 4 cleanup
    }
};
