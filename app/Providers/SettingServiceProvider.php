<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Setting;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view){
            $setting = Setting::find('1');
            return $view->with('setting', $setting);
        });
    }
}
