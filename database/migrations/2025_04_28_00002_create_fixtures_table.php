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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_id')->constrained('teams');
            $table->foreignId('away_id')->constrained('teams');
            $table->date('date');
            $table->integer('week');
            $table->boolean('is_played')->default(false);
            $table->timestamps();

            // composite index
            $table->index(['week', 'home_id', 'away_id']);
            $table->index(['week', 'is_played']);
            $table->index(['home_id', 'away_id', 'is_played']);
            $table->index(['week', 'home_id', 'away_id', 'is_played']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
