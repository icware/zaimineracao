<?php

use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (class_exists('App\Packages\System\Migrations\SystemMigrations')) {

            require_once base_path('app/Packages/System/Migrations/SystemMigrations.php');
        
            (new App\Packages\System\Migrations\SystemMigrations())->up();
        }

        if (class_exists('App\Packages\Service\Migrations\ServiceMigrations')) {

            require_once base_path('app/Packages/Service/Migrations/ServiceMigrations.php');
        
            (new App\Packages\Service\Migrations\ServiceMigrations())->up();

        }

        if (class_exists('App\Packages\Company\Migrations\CompanyMigrations')) {

            require_once base_path('app/Packages/Company/Migrations/CompanyMigrations.php');
        
            (new App\Packages\Company\Migrations\CompanyMigrations())->up();

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (class_exists('App\Packages\Company\Migrations\CompanyMigrations')) {

            require_once base_path('app/Packages/Company/Migrations/CompanyMigrations.php');
        
            (new App\Packages\Company\Migrations\CompanyMigrations())->down();

        }

        if (class_exists('App\Packages\Service\Migrations\ServiceMigrations')) {

            require_once base_path('app/Packages/Service/Migrations/ServiceMigrations.php');
        
            (new App\Packages\Service\Migrations\ServiceMigrations())->down();

        }

        if (class_exists('App\Packages\System\Migrations\SystemMigrations')) {

            require_once base_path('app/Packages/System/Migrations/SystemMigrations.php');
        
            (new App\Packages\System\Migrations\SystemMigrations())->down();

        }
    }
};
