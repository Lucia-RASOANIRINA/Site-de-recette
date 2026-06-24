<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('recipe_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recette_id')->index();
            $table->string('recette_nom');   // nom de la recette aimee (saisi par le visiteur)
            $table->string('email');         // email du visiteur
            $table->timestamp('created_at')->useCurrent();

            // Anti-redondance : un email ne peut voter qu'une fois par recette
            // (mais peut voter pour plusieurs recettes differentes)
            $table->unique(['recette_id', 'email']);
        });
    }
    public function down(): void { Schema::dropIfExists('recipe_votes'); }
};
