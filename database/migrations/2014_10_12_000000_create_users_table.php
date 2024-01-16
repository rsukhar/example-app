<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $userRoles = config('models.users.roles', ['admin' => 'Админ']);
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->char('phone', 11)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_blocked')->default(FALSE);
            $table->string('password');
            $table->enum('role', array_keys($userRoles))->default(array_key_first($userRoles));
            $table->date('birthday')->nullable();
            $table->jsonb('meta')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
