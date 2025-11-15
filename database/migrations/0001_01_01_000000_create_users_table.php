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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->string('id', 20)->primary();
                $table->string('name', 100);
                $table->string('email', 100)->unique();
                $table->string('phone', 15)->nullable();
                $table->enum('identification_type', ['cc', 'ce', 'ti', 'pasaporte'])->default('cc');
                $table->string('identification_number', 20)->nullable();
                $table->string('password', 255)->nullable();
                $table->string('image', 255)->nullable();
                $table->enum('role', ['customer', 'admin'])->default('customer');
                $table->boolean('is_blocked')->default(false);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
                $table->dateTime('last_access')->nullable();
                $table->string('remember_token', 255)->nullable();
                $table->dateTime('token_expiry')->nullable();
            });
        }

        if (!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('user_id', 20);
                $table->string('token', 255);
                $table->dateTime('expires_at');
                $table->boolean('is_used')->default(false);
                $table->timestamp('created_at')->useCurrent();
                $table->index('user_id');
            });
        }

        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('user_id', 20)->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('sessions');
    }
};
