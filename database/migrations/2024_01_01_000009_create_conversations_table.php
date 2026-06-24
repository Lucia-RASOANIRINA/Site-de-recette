<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_one')->nullable()->index();
            $table->unsignedBigInteger('user_two')->nullable()->index();
            $table->unsignedBigInteger('group_id')->nullable()->index();
            $table->boolean('is_group')->default(false);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('conversations'); }
};
