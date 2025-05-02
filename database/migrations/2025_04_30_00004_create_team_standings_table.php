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
        Schema::create('team_standings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams');
            $table->smallInteger('wins')->default(0);
            $table->smallInteger('draws')->default(0);
            $table->smallInteger('losses')->default(0);
            $table->mediumInteger('goals_for')->default(0);
            $table->mediumInteger('goals_against')->default(0);
            $table->mediumInteger('goal_difference')->default(0);
            $table->mediumInteger('points')->default(0);
            $table->mediumInteger('matches_played')->default(0);
            $table->mediumInteger('position')->nullable();
            $table->timestamps();

            // composite index
            $table->index(['team_id', 'position']);
            $table->index(['team_id', 'position', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_standings');
    }
};
