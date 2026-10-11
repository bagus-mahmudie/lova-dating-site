<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delete_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Simpan email snapshot agar tetap bisa dilihat meski user terhapus
            $table->string('user_email')->nullable();

            // Alasan tambahan dari user saat hapus akun
            $table->text('reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delete_feedback');
    }
};
