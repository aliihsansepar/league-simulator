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
            $table->foreignId('fixture_id')->constrained('fixtures')->cascade('onDelete');
            $table->foreignId('home_id')->constrained('teams')->cascade('onDelete');
            $table->foreignId('away_id')->constrained('teams')->cascade('onDelete');
            $table->smallInteger('home_score')->default(0);
            $table->smallInteger('away_score')->default(0);
            $table->timestamps();
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
