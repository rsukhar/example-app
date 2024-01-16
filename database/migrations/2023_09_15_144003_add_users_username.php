<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['telegram']);
            $table->renameColumn('telegram', 'username');
            $table->unique(['username']);
        });
        DB::statement('ALTER TABLE "public"."users" DROP CONSTRAINT IF EXISTS "users_role_check"');
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->renameColumn('username', 'telegram');
            $table->unique(['telegram']);
        });
    }
};
