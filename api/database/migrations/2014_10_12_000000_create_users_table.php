<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('code')->unique();
            $table->string('phone')->nullable();
            $table->string('birth')->nullable();
            $table->boolean('active')->default(false);
            $table->string('company')->nullable();
            $table->boolean('super')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('update_password_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_layout_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('theme')->default('aura-dark-blue');
            $table->integer('scale')->default(16);
            $table->boolean('dark_mode')->default(false);
            $table->string('menu_mode')->default('static');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_layout_configs');
        Schema::dropIfExists('users');
    }
};
