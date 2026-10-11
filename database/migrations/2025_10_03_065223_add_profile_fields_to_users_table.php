<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Profil dasar
            $table->unsignedTinyInteger('age')->nullable()->after('password');         // 0–255 cukup untuk usia
            $table->string('job', 100)->nullable()->after('age');
            $table->string('location', 150)->nullable()->after('job');
            $table->text('about')->nullable()->after('location');

            // Preferensi/kepribadian
            $table->json('hobby')->nullable()->after('about');                         // simpan array/list hobi
            $table->json('lifestyle')->nullable()->after('hobby');                     // mis. ["vegan","early-bird"]
            $table->string('personality', 100)->nullable()->after('lifestyle');        // mis. "INTJ", "extrovert"
            $table->string('love_language', 50)->nullable()->after('personality');     // mis. "quality_time"
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'age',
                'job',
                'location',
                'about',
                'hobby',
                'lifestyle',
                'personality',
                'love_language',
            ]);
        });
    }
};
