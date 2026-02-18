<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            // Client Intelligence fields
            $table->text('relationship_history')->nullable()->comment('History with this client');
            $table->text('key_decision_makers')->nullable()->comment('Who makes decisions');
            $table->text('past_performance')->nullable()->comment('Our past performance with this client');
            $table->text('client_preferences')->nullable()->comment('Known preferences and priorities');
            $table->boolean('incumbent_advantage')->default(false);
            $table->text('political_landscape')->nullable()->comment('Internal politics and stakeholders');
        });
    }

    public function down(): void
    {
        Schema::table('tender_opportunities', function (Blueprint $table) {
            $table->dropColumn([
                'relationship_history',
                'key_decision_makers',
                'past_performance',
                'client_preferences',
                'incumbent_advantage',
                'political_landscape',
            ]);
        });
    }
};
