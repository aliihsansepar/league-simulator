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
        Schema::create('standings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('seasons');
            $table->foreignId('team_id')->constrained('teams');
            $table->integer('played_matches')->default(0);
            $table->smallInteger('wins')->default(0);
            $table->smallInteger('draws')->default(0);
            $table->smallInteger('losses')->default(0);
            $table->mediumInteger('goals_for')->default(0);
            $table->mediumInteger('goals_against')->default(0);
            $table->mediumInteger('points')->default(0);
            $table->mediumInteger('position')->nullable();
            $table->timestamps();

            // composite index
            $table->index(['season_id', 'team_id']);
            $table->index(['season_id', 'team_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standings');
    }
};
