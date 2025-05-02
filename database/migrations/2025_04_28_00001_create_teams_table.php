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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->unique();
            $table->string('short_name', 3)->unique();
            $table->float('goalkeeper_strength', 3, 2)->default(0);
            $table->float('defense_strength', 3, 2)->default(0);
            $table->float('midfield_strength', 3, 2)->default(0);
            $table->float('attack_strength', 3, 2)->default(0);
            $table->timestamps();

            // composite index
            $table->index(['name', 'short_name']);
            $table->index(['name', 'short_name', 'created_at']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
