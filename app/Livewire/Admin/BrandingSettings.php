<?php

namespace App\Livewire\Admin;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class BrandingSettings extends Component
{
    use WithFileUploads;

    public string $company_name = '';

    public string $system_name = '';

    public string $support_email = '';

    public string $primary_color = '#4F46E5';

    public string $secondary_color = '#111827';

    public string $login_title = '';

    public string $login_message = '';

    public string $footer_text = '';

    public $logo = null;

    public $favicon = null;


    public function mount(): void
    {
        $this->authorizeAdmin();

        $settings = CompanySetting::query()->firstOrCreate(
            ['id' => 1],
            CompanySetting::defaults()
        );

        $this->company_name = $settings->company_name;

        $this->system_name = $settings->system_name;

        $this->support_email =
            $settings->support_email ?? '';

        $this->primary_color =
            $settings->primary_color ?? '#4F46E5';

        $this->secondary_color =
            $settings->secondary_color ?? '#111827';

        $this->login_title =
            $settings->login_title ?? '';

        $this->login_message =
            $settings->login_message ?? '';

        $this->footer_text =
            $settings->footer_text ?? '';
    }


    public function updatedLogo(): void
    {
        $this->validateOnly(
            'logo',
            $this->rules(),
            $this->messages()
        );
    }


    public function updatedFavicon(): void
    {
        $this->validateOnly(
            'favicon',
            $this->rules(),
            $this->messages()
        );
    }


    public function save(): void
    {
        $this->authorizeAdmin();

        $validated = $this->validate(
            $this->rules(),
            $this->messages()
        );

        $settings = CompanySetting::query()
            ->firstOrCreate(
                ['id' => 1],
                CompanySetting::defaults()
            );

        $oldLogoPath = $settings->logo_path;

        $oldFaviconPath = $settings->favicon_path;

        $newLogoPath = null;

        $newFaviconPath = null;

        try {

            /*
            |--------------------------------------------------------------------------
            | Logo
            |--------------------------------------------------------------------------
            */

            if ($this->logo) {

                $extension = strtolower(
                    $this->logo->getClientOriginalExtension()
                );

                $newLogoPath = $this->logo->storeAs(
                    'branding',
                    'logo-'
                    .Str::uuid()
                    .'.'
                    .$extension,
                    'public'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Favicon
            |--------------------------------------------------------------------------
            */

            if ($this->favicon) {

                $extension = strtolower(
                    $this->favicon->getClientOriginalExtension()
                );

                $newFaviconPath = $this->favicon->storeAs(
                    'branding',
                    'favicon-'
                    .Str::uuid()
                    .'.'
                    .$extension,
                    'public'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Guardar configuración
            |--------------------------------------------------------------------------
            */

            $settings->fill([
                'company_name' =>
                    $validated['company_name'],

                'system_name' =>
                    $validated['system_name'],

                'support_email' =>
                    $validated['support_email'] ?: null,

                'primary_color' =>
                    strtoupper(
                        $validated['primary_color']
                    ),

                'secondary_color' =>
                    strtoupper(
                        $validated['secondary_color']
                    ),

                'login_title' =>
                    $validated['login_title'] ?: null,

                'login_message' =>
                    $validated['login_message'] ?: null,

                'footer_text' =>
                    $validated['footer_text'] ?: null,

                'logo_path' =>
                    $newLogoPath
                    ?? $settings->logo_path,

                'favicon_path' =>
                    $newFaviconPath
                    ?? $settings->favicon_path,
            ]);

            $settings->save();


            /*
            |--------------------------------------------------------------------------
            | Limpiar archivos anteriores
            |--------------------------------------------------------------------------
            */

            if (
                $newLogoPath
                && $oldLogoPath
                && $oldLogoPath !== $newLogoPath
            ) {
                Storage::disk('public')
                    ->delete($oldLogoPath);
            }


            if (
                $newFaviconPath
                && $oldFaviconPath
                && $oldFaviconPath !== $newFaviconPath
            ) {
                Storage::disk('public')
                    ->delete($oldFaviconPath);
            }


        } catch (Throwable $exception) {

            /*
             * Si falla la base de datos después de subir
             * un archivo nuevo, eliminamos ese archivo.
             */

            if ($newLogoPath) {
                Storage::disk('public')
                    ->delete($newLogoPath);
            }

            if ($newFaviconPath) {
                Storage::disk('public')
                    ->delete($newFaviconPath);
            }

            report($exception);

            $this->addError(
                'form',
                'No se pudo guardar la personalización.'
            );

            return;
        }


        session()->flash(
            'success',
            'Personalización actualizada correctamente.'
        );


        /*
         * Hacemos recarga completa para que navbar,
         * favicon y demás componentes reciban
         * inmediatamente la nueva configuración global.
         */

        redirect()->route(
            'admin.branding.edit'
        );
    }


    public function removeLogo(): void
    {
        $this->authorizeAdmin();

        $settings = CompanySetting::query()
            ->firstOrCreate(
                ['id' => 1],
                CompanySetting::defaults()
            );

        if ($settings->logo_path) {

            Storage::disk('public')
                ->delete(
                    $settings->logo_path
                );

            $settings->update([
                'logo_path' => null,
            ]);
        }

        $this->reset('logo');

        session()->flash(
            'success',
            'Logo eliminado.'
        );
    }


    public function removeFavicon(): void
    {
        $this->authorizeAdmin();

        $settings = CompanySetting::query()
            ->firstOrCreate(
                ['id' => 1],
                CompanySetting::defaults()
            );

        if ($settings->favicon_path) {

            Storage::disk('public')
                ->delete(
                    $settings->favicon_path
                );

            $settings->update([
                'favicon_path' => null,
            ]);
        }

        $this->reset('favicon');

        session()->flash(
            'success',
            'Favicon eliminado.'
        );
    }


    private function rules(): array
    {
        return [
            'company_name' => [
                'required',
                'string',
                'max:150',
            ],

            'system_name' => [
                'required',
                'string',
                'max:150',
            ],

            'support_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'primary_color' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],

            'secondary_color' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],

            'login_title' => [
                'nullable',
                'string',
                'max:150',
            ],

            'login_message' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'footer_text' => [
                'nullable',
                'string',
                'max:255',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'favicon' => [
                'nullable',
                'file',
                'mimes:png,ico',
                'max:1024',
            ],
        ];
    }


    private function messages(): array
    {
        return [
            'company_name.required' =>
                'El nombre de la empresa es obligatorio.',

            'system_name.required' =>
                'El nombre del sistema es obligatorio.',

            'support_email.email' =>
                'El correo de soporte no es válido.',

            'primary_color.regex' =>
                'El color principal debe tener formato hexadecimal, por ejemplo #4F46E5.',

            'secondary_color.regex' =>
                'El color secundario debe tener formato hexadecimal, por ejemplo #111827.',

            'logo.image' =>
                'El logo debe ser una imagen.',

            'logo.mimes' =>
                'El logo debe ser JPG, PNG o WEBP.',

            'logo.max' =>
                'El logo no puede superar 2 MB.',

            'favicon.mimes' =>
                'El favicon debe ser PNG o ICO.',

            'favicon.max' =>
                'El favicon no puede superar 1 MB.',
        ];
    }


    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check()
            && Auth::user()->isAdmin(),
            403
        );
    }


    public function render()
    {
        $settings = CompanySetting::query()
            ->firstOrCreate(
                ['id' => 1],
                CompanySetting::defaults()
            );

        return view(
            'livewire.admin.branding-settings',
            [
                'settings' => $settings,

                'currentLogoUrl' =>
                    $settings->logo_path
                        ? Storage::disk('public')
                            ->url($settings->logo_path)
                        : null,

                'currentFaviconUrl' =>
                    $settings->favicon_path
                        ? Storage::disk('public')
                            ->url($settings->favicon_path)
                        : null,
            ]
        );
    }
}