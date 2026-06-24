<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recette_id')->index();
            $table->string('nom', 150);
            $table->string('quantite', 100);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('ingredients'); }
};
