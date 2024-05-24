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
            $table->boolean('status')->default(false); // Company status (active/inactive)
            $table->string('code')->nullable()->unique(); // Unique company code
            $table->string('responsible_cpf')->nullable(); // CPF of the responsible person
            $table->string('cnpj')->nullable()->unique(); // Unique CNPJ of the company
            $table->string('company_name'); // Official company name (Razão Social)
            $table->string('address_type'); // Type of address (Tipo Logradouro)
            $table->string('address'); // Street address (Logradouro)
            $table->string('number'); // Address number (Número)
            $table->string('complement')->nullable(); // Address complement (Complemento)
            $table->string('neighborhood'); // Neighborhood (Bairro)
            $table->string('postal_code'); // Postal code (CEP)
            $table->string('state'); // State (Estado)
            $table->string('country'); // Country (País)
            $table->string('phone'); // Contact phone number (Telefone)
            $table->string('email'); // Contact email (Email)
            $table->string('trading_name')->nullable(); // Trading name (Nome Fantasia)
            $table->string('registration_status'); // Registration status (Situação Cadastral)
            $table->timestamps(); // Timestamps (created_at and updated_at)
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
