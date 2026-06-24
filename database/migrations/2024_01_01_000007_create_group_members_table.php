<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            // Anti-redondance : un membre ne peut rejoindre un groupe qu'une fois
            $table->unique(['group_id', 'user_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('group_members'); }
};
