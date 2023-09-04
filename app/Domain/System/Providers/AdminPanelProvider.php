<?php

namespace App\Domain\System\Providers;

use App\Domain\System\Support\Domain;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    /**
     * @param  Panel $panel
     * @return Panel
     */
    public function panel(Panel $panel) : Panel
    {
        $panel
            ->default()
            ->id('panel')
            ->path('panel')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
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
            ]);

        $this->discovery('Pages')->each(function ($namespace, $path) use ($panel) {
            $panel->discoverPages($path, $namespace);
        });

        $this->discovery('Widgets')->each(function ($namespace, $path) use ($panel) {
            $panel->discoverWidgets($path, $namespace);
        });

        $this->discovery('Resources')->each(function ($namespace, $path) use ($panel) {
            $panel->discoverResources($path, $namespace);
        });

        return $panel;
    }

    /**
     * @param  string $type
     * @return mixed
     */
    private function discovery(string $type) : mixed
    {
        return Domain::getDomainName()->flatMap(function (string $domain) use ($type) {
            $path = sprintf('Domain/%s/Filament/%s', $domain, $type);

            return [
                app_path($path) => 'App\\' . str_replace('/', '\\', $path),
            ];
        });
    }
}
