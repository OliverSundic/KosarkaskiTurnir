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
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->foreignId('domaci_tim_id')->constrained('teams');
            $table->foreignId('strani_tim_id')->constrained('teams');
            // Napravio sam referee_id da bude nullable jer ga ne dodeljujemo odmah pri generisanju
            $table->foreignId('referee_id')->nullable()->constrained('users');
            $table->string('mesto', 255);
            $table->string('rezultat', 20)->default('0:0');
            // Proveri da li je "zakazana" ili "zakazano" - u kontroleru koristiš "zakazano"
            $table->enum('status', ["zakazano","u_toku","zavrseno","otkazano"])->default('zakazano');
            $table->integer('kolo')->unsigned(); // DODAJ OVO jer ga koristiš u controlleru
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
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
