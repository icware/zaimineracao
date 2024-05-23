<?php

namespace App\Packages\Company\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompanyMigrations extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(false);
            $table->string('code')->nullable()->uniqid();
            $table->string('client')->nullable();
            $table->string('cnpj')->nullable()->uniqid();
            $table->timestamps();
        });

        Schema::create('associates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('invite');
            $table->boolean('enabled')->default(false);
            $table->timestamps();

            $table->unique(['company_id', 'user_id']);
        });

        Schema::create('displays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->boolean('visible')->default(false);
            $table->timestamps();
        });

        Schema::create('display_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('display_id')->constrained()->onDelete('cascade');
            $table->integer('service_code');            
            $table->integer('source_id');
            $table->string('source_key');
            $table->string('source_format');
            $table->boolean('visible')->default(false);
            $table->json('settings')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('associates');
        Schema::dropIfExists('display_sources');
        Schema::dropIfExists('displays');
        Schema::dropIfExists('companies');
    }
};