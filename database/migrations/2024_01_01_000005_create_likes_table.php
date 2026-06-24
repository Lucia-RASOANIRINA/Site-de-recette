<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('recette_id')->nullable()->index();
            $table->unsignedBigInteger('post_id')->nullable()->index();
            $table->timestamp('created_at')->useCurrent();

            // Anti-redondance : un utilisateur ne peut liker qu'une seule fois
            // (en SQLite/MySQL les NULL sont distincts, donc like recette et like post coexistent)
            $table->unique(['user_id', 'recette_id']);
            $table->unique(['user_id', 'post_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('likes'); }
};
