<?php

namespace App\Packages\Service\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ServiceMigrations extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->uniqid();
            $table->boolean('enabled')->default(true);
            $table->unsignedBigInteger('server');
            $table->string('type')->nullable(); // Oprações GET SET, ALL
            $table->json('settings')->nullable();
            $table->foreign('server')->references('id')->on('servers');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
}
;