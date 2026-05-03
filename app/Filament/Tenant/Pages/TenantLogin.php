<?php

namespace App\Filament\Tenant\Pages;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Component;
use Filament\Http\Responses\Auth\LoginResponse;
use Filament\Pages\Auth\Login;
use Illuminate\Validation\ValidationException;

class TenantLogin extends Login
{
    public function authenticate(): ?LoginResponse
{
    try {
        $data = $this->form->getState();
        
        // 1. Cek credentials tanpa login dulu
        if (!Auth::attempt([
            'email' => $data['email'], 
            'password' => $data['password']
        ])) {
            throw ValidationException::withMessages([
                'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }
        
        // 2. Ambil user
        /** @var \App\Models\Tenants\User $user */
        $user = Auth::user();
        
        // 3. CEK PERMISSION - INI YANG PENTING
        if (!$user->can('akses aplikasi web')) {
            Auth::logout(); // logout langsung
            
            throw ValidationException::withMessages([
                'data.email' => 'You do not have permission to access the web app',
            ]);
        }
        
        // 4. Remember me
        if (filled($data['remember'] ?? null)) {
            Auth::login($user, true);
        }
        
        // 5. Update profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->getKey()],
            ['timezone' => 'Asia/Jakarta']
        );
        
        // 6. Session regenerate (security)
        session()->regenerate();
        
        // 7. Return response
        return app(LoginResponse::class);
        
    } catch (ValidationException $e) {
        throw $e;
    }
}

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }

        if (app()->environment('demo')) {
            $this->form->fill([
                'email' => 'demo@lakasir.com',
                'password' => 'passwordsangatrahasia'
            ]);
        }
    }
}
