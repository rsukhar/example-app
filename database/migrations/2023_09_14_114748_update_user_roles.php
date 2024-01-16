<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Обновленный список доступных ролей
            $table->string('role')->default('admin')->change();
            // Реферрал
            $table->foreignId('affiliate_id')->nullable()->after('remember_token');
            // Телега
            $table->renameColumn('name', 'telegram');
            $table->unique(['telegram']);
            // Удаляем телефон
            $table->dropUnique(['phone']);
            $table->dropColumn('phone');
            // Упрощаем тему с email
            $table->dropUnique(['email']);
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('affiliate_id');
            $table->dropUnique(['telegram']);
            $table->renameColumn('telegram', 'name');
            $table->char('phone', 11)->unique()->nullable();
            $table->string('email')->nullable(false)->unique()->change();
            $table->string('role')->default('admin')->change();
        });
    }
};
