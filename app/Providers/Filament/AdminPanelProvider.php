<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Filament\Navigation\MenuItem;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Auth\Login::class)
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->brandLogo(fn() => view('filament.components.brand-logo'))
            ->brandName(function () {
                return App::make('settingItems')['site_name']->value ?? 'Site Name';
            })
            ->favicon(function () {
                return App::make('settingItems')['favicon']->value ?? asset('/assets/images/favicon.png');
            })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
            ])
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle('My Profile')
                    ->setNavigationLabel('My Profile')
                    // ->setNavigationGroup('Group Profile')
                    ->setIcon('heroicon-o-user')
                    // ->setSort(10)
                    // ->canAccess(fn() => auth()->user()->id === 1)
                    ->shouldRegisterNavigation(false)
                    // ->shouldShowEmailForm()
                    ->shouldShowDeleteAccountForm(false)
                    // ->shouldShowSanctumTokens()
                    // ->shouldShowBrowserSessionsForm() 
                    ->shouldShowAvatarForm(value: true, directory: 'avatars', rules: 'mimes:jpeg,png|max:2048')
                // ->customProfileComponents([
                //     \App\Livewire\CustomProfileComponent::class,
                // ])
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => Auth::user()->name)
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle'),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->sidebarFullyCollapsibleOnDesktop()
            ->sidebarWidth('16rem');
    }
}
