<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('code')->nullable()->uniqid();
            $table->timestamps();
        });
                
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->uniqid();
            $table->string('type')->nullable();
            $table->timestamps();
        });

        Schema::create('services_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('server');
            $table->unsignedBigInteger('service');
            $table->foreign('server')->references('id')->on('servers');
            $table->foreign('service')->references('id')->on('services');
            $table->string('outher_urls');  
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('services_settings');
        Schema::dropIfExists('services');
    }
};