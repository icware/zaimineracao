<?php

namespace App\Packages\System\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SystemMigrations extends Migration
{
    public function up(): void
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->timestamps();
        });   
        
        Schema::create('system_codes', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('code')->nullable()->uniqid();
            $table->timestamps();
        });   

        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->enum('type', ['internal', 'external'])->nullable();
            $table->string('address');
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('servers');
        Schema::dropIfExists('system_codes');       
        Schema::dropIfExists('systems');
    }
};