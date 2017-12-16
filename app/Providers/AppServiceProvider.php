<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Channel;
use Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $channels = \Cache::rememberForever('channels', function () {
                return Channel::all();
            });
            return $view->with('channels', $channels);
        });

        Gate::before(function ($user) {
            if ($user->id === 1) {
                return true;
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
