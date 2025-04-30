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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('seasons');
            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('away_team_id')->constrained('teams');
            $table->timestamp('match_date');
            $table->integer('home_goals');
            $table->integer('away_goals');
            $table->boolean('is_played')->default(false);
            $table->timestamps();

            // composite index
            $table->index(['season_id', 'home_team_id', 'away_team_id']);
            $table->index(['season_id', 'home_team_id', 'away_team_id', 'is_played']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
