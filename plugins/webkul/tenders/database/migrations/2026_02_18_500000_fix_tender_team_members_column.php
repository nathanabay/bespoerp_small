<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tender_team_members', function (Blueprint $table) {
            $table->renameColumn('tender_opportunity_id', 'tender_id');
        });
    }

    public function down(): void
    {
        Schema::table('tender_team_members', function (Blueprint $table) {
            $table->renameColumn('tender_id', 'tender_opportunity_id');
        });
    }
};
