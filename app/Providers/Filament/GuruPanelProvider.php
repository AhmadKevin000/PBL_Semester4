<?php

namespace App\Providers\Filament;

use App\Filament\Guru\Pages\Dashboard;
use App\Http\Middleware\EnsureUserIsGuru;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class GuruPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('guru')
            ->path('guru')
            ->authGuard('guru')
            ->login()
            ->colors([
                'primary' => Color::Emerald,
                'gray'    => Color::Zinc,
                'warning' => Color::Amber,
            ])
            ->font('Inter')
            ->brandName('Portal Guru')
            ->discoverPages(in: app_path('Filament/Guru/Pages'), for: 'App\\Filament\\Guru\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Guru/Widgets'), for: 'App\\Filament\\Guru\\Widgets')
            ->widgets([])
            // Tidak ada resources — panel guru hanya dashboard
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureUserIsGuru::class,
            ]);
    }
}
