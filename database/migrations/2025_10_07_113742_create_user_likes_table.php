<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::create('user_likes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('liker_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('liked_id')->constrained('users')->cascadeOnDelete();
        $table->timestamps();

        $table->unique(['liker_id', 'liked_id']); // satu akun hanya bisa like satu kali
    });
}

public function down(): void
{
    Schema::dropIfExists('user_likes');
}

};
