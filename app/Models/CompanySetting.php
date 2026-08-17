<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'system_name',
        'support_email',
        'logo_path',
        'favicon_path',
        'primary_color',
        'secondary_color',
        'login_title',
        'login_message',
        'footer_text',
    ];

    public static function defaults(): array
    {
        return [
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
        ];
    }

    public static function current(): self
    {
        return static::query()->find(1)
            ?? new static(static::defaults());
    }
}