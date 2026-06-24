<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('recettes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('titre', 200);
            $table->text('description');
            $table->longText('instructions');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('recettes'); }
};
