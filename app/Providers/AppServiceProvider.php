<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                'edit_profile' => UserMenuItem::make()
                    ->label('Edit profile')
                    ->url(route('filament.pages.profile'))
                    ->icon('heroicon-o-user-circle')
                    ->sort(0),
            ]);

            Filament::registerNavigationItems([
                NavigationItem::make('Website')
                    ->url(url('/'))
                    ->icon('heroicon-o-globe-alt')
                    ->sort(3),
            ]);
        });
    }
}
