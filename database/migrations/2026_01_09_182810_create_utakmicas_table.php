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
        Schema::disableForeignKeyConstraints();

        Schema::create('utakmicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained();
            $table->foreignId('domaci_tim_id')->constrained('teams');
            $table->foreignId('strani_tim_id')->constrained('teams');
            $table->foreignId('referee_id')->constrained('users');
            $table->string('mesto', 255);
            $table->string('rezultat', 20)->nullable();
            $table->enum('status', ["zakazana","u_toku","zavrsena","otkazana"])->default('zakazana');
            $table->foreignId('user_id');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utakmicas');
    }
};
