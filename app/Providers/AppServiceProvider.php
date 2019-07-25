<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // format money
        Blade::directive('money', function ($money) {
            return "<?php echo number_format($money, 2); ?>";
        });        

        $this->app['events']->listen('session.started', function () {
            \View::share('is_case_was_notified', session('is_case_was_notified'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Http\Middleware\StartSession');
    }
}
