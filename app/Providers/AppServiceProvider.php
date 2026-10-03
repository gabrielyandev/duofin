<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production') || str_starts_with((string) config('app.url'), 'https://') || request()->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        $appUrl = config('app.url');
        if (! empty($appUrl) && $appUrl !== 'http://localhost' && $appUrl !== 'http://localhost:8000') {
            URL::forceRootUrl($appUrl);
        } elseif ($host = request()->header('host')) {
            $isHttps = $this->app->environment('production') || request()->header('x-forwarded-proto') === 'https';
            URL::forceRootUrl(($isHttps ? 'https://' : 'http://').$host);
        }
    }
}
