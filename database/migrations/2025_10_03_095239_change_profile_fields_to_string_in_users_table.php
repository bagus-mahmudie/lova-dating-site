<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Ubah kolom yang mungkin masih JSON menjadi VARCHAR
        if (Schema::hasColumn('users', 'hobby')) {
            DB::statement("ALTER TABLE users MODIFY hobby VARCHAR(100) NULL");
        }
        if (Schema::hasColumn('users', 'lifestyle')) {
            DB::statement("ALTER TABLE users MODIFY lifestyle VARCHAR(100) NULL");
        }
        if (Schema::hasColumn('users', 'personality')) {
            DB::statement("ALTER TABLE users MODIFY personality VARCHAR(100) NULL");
        }
    }

    public function down(): void
    {
        // Kembalikan ke JSON kalau perlu rollback
        if (Schema::hasColumn('users', 'hobby')) {
            DB::statement("ALTER TABLE users MODIFY hobby JSON NULL");
        }
        if (Schema::hasColumn('users', 'lifestyle')) {
            DB::statement("ALTER TABLE users MODIFY lifestyle JSON NULL");
        }
        if (Schema::hasColumn('users', 'personality')) {
            DB::statement("ALTER TABLE users MODIFY personality JSON NULL");
        }
    }
};
