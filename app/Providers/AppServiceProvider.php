<?php

namespace App\Providers;

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
        // Register Observers
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);

        // Share settings with all views
        view()->composer('*', function ($view) {
            $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
            $view->with('settings', $settings);
        });

        // Custom Blade directive for translations
        \Blade::directive('trans', function ($expression) {
            return "<?php echo __($expression); ?>";
        });

        // Share current locale and RTL status with all views
        view()->composer('*', function ($view) {
            $locale = app()->getLocale();
            $isRtl = in_array($locale, ['ar']); // Arabic is RTL
            $view->with('currentLocale', $locale);
            $view->with('isRtl', $isRtl);
        });
    }
}
