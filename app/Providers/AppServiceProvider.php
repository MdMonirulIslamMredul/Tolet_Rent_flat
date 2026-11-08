<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        Paginator::useBootstrapFive();

        if (
            env('FORCE_HTTPS', false) ||
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            request()->header('X-Forwarded-Proto') === 'https' ||
            (app()->environment('production') && !app()->isLocal() && !str_contains((string) config('app.url'), 'localhost'))
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            static $cachedSettings = null;
            if ($cachedSettings === null) {
                try {
                    if (Schema::hasTable('settings')) {
                        $cachedSettings = Setting::first() ?? false;
                    }
                } catch (\Throwable $e) {
                    $cachedSettings = false;
                }
            }
            $view->with('settings', $cachedSettings ?: null);
        });

        View::composer(['admin.partials.sidebar', 'admin.partials.topbar'], function ($view) {
            $unreadContactsCount = 0;
            $pendingVerificationsCount = 0;
            $pendingApplicationsCount = 0;
            try {
                if (Schema::hasTable('contacts')) {
                    $unreadContactsCount = Contact::unread()->count();
                }
                if (Schema::hasTable('user_verifications')) {
                    $pendingVerificationsCount = \App\Models\Rental\UserVerification::where('status', 'pending')->count();
                }
                if (Schema::hasTable('rental_applications')) {
                    $pendingApplicationsCount = \App\Models\Rental\RentalApplication::where('status', 'pending')->count();
                }
            } catch (\Throwable $e) {
                $unreadContactsCount = 0;
                $pendingVerificationsCount = 0;
                $pendingApplicationsCount = 0;
            }
            $view->with([
                'unreadContactsCount' => $unreadContactsCount,
                'pendingVerificationsCount' => $pendingVerificationsCount,
                'pendingApplicationsCount' => $pendingApplicationsCount,
            ]);
        });
    }
}
