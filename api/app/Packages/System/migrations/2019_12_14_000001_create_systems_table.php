<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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

    }

    public function down(): void
    {
        Schema::dropIfExists('system_codes');
        Schema::dropIfExists('systems');
    }
};