<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();

            $table->string('company_name', 150);
            $table->string('system_name', 150);

            $table->string('support_email')->nullable();

            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();

            $table->string('primary_color', 20)
                ->default('#4F46E5');

            $table->string('secondary_color', 20)
                ->default('#111827');

            $table->string('login_title', 150)
                ->nullable();

            $table->text('login_message')
                ->nullable();

            $table->string('footer_text', 255)
                ->nullable();

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Configuración inicial
        |--------------------------------------------------------------------------
        |
        | Cada instalación tendrá un único registro con ID = 1.
        |
        */

        DB::table('company_settings')->insert([
            'id' => 1,

            'company_name' => 'Mi Empresa',

            'system_name' => 'Portal de Soporte',

            'support_email' => null,

            'logo_path' => null,

            'favicon_path' => null,

            'primary_color' => '#4F46E5',

            'secondary_color' => '#111827',

            'login_title' => 'Bienvenido',

            'login_message' =>
                'Accede al portal para gestionar tus solicitudes de soporte.',

            'footer_text' => null,

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};