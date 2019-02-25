<?php

namespace App\Providers;

use App\Services\ApiHelper;
use Illuminate\Support\ServiceProvider;

class ApiHelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ApiHelper::class, function (){
            return new ApiHelper();
        });
    }
}
