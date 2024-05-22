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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->uniqid();
            $table->string('client')->nullable();
            $table->string('cnpj')->nullable()->uniqid();
            $table->timestamps();
        });

        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->uniqid();
            $table->string('client')->nullable();
            $table->string('cnpj')->nullable()->uniqid();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('user_settings');
    }
};