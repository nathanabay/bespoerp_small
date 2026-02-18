<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tender_team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tender_opportunities')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['Bid Manager', 'Technical Lead', 'Pricing Lead', 'Proposal Writer', 'Reviewer', 'Subject Matter Expert', 'Other'])->default('Other');
            $table->text('responsibilities')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_team_members');
    }
};
