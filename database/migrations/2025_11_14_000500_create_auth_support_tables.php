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
        if (!Schema::hasTable('access_tokens')) {
            Schema::create('access_tokens', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('user_id', 20);
                $table->string('token', 255);
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->dateTime('created_at')->useCurrent();
                $table->dateTime('expires_at');
                $table->boolean('is_revoked')->default(false);

                $table->index('user_id');
                $table->index('token');
            });
        }

        if (!Schema::hasTable('user_addresses')) {
            Schema::create('user_addresses', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('user_id', 20);
                $table->enum('address_type', ['casa', 'apartamento', 'oficina', 'otro'])->default('casa');
                $table->string('alias', 50);
                $table->string('recipient_name', 100);
                $table->string('recipient_phone', 15);
                $table->string('address', 255);
                $table->string('complement', 100)->nullable();
                $table->string('neighborhood', 100);
                $table->enum('building_type', ['casa', 'apartamento', 'edificio', 'conjunto', 'local'])->default('casa');
                $table->string('building_name', 100)->nullable();
                $table->string('apartment_number', 20)->nullable();
                $table->text('delivery_instructions')->nullable();
                $table->boolean('is_default')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
                $table->decimal('gps_latitude', 10, 8)->nullable();
                $table->decimal('gps_longitude', 11, 8)->nullable();
                $table->decimal('gps_accuracy', 10, 2)->nullable();
                $table->dateTime('gps_timestamp')->nullable();
                $table->boolean('gps_used')->default(false);

                $table->index('user_id');
                $table->index(['user_id', 'is_default']);
            });
        }

        if (!Schema::hasTable('user_applied_discounts')) {
            Schema::create('user_applied_discounts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('user_id', 20);
                $table->unsignedInteger('discount_code_id');
                $table->string('discount_code', 20);
                $table->decimal('discount_amount', 10, 2);
                $table->timestamp('applied_at')->useCurrent();
                $table->dateTime('expires_at');
                $table->boolean('is_used')->default(false);
                $table->dateTime('used_at')->nullable();

                $table->index('user_id');
                $table->index('discount_code_id');
                $table->index(['user_id', 'discount_code']);
            });
        }

        if (!Schema::hasTable('login_attempts')) {
            Schema::create('login_attempts', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('username', 255);
                $table->string('ip_address', 45);
                $table->dateTime('attempt_date')->useCurrent();

                $table->index(['username', 'attempt_date']);
                $table->index(['ip_address', 'attempt_date']);
            });
        }

        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_tokens');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('user_applied_discounts');
        Schema::dropIfExists('login_attempts');
        Schema::dropIfExists('personal_access_tokens');
    }
};
