<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // media & identitas
            $table->string('cover_image')->nullable()->after('avatar');
            $table->string('gender', 20)->nullable()->after('age');
            $table->string('religion', 50)->nullable()->after('job');
            $table->string('languages', 150)->nullable()->after('religion');

            // social links (JSON: {"instagram":"...","tiktok":"...","facebook":"..."} )
            $table->json('social_links')->nullable()->after('languages');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cover_image',
                'gender',
                'religion',
                'languages',
                'social_links',
            ]);
        });
    }
};
