<?php

namespace App\Providers;

use App\Services\ApiHelperService;
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
        $this->app->bind(ApiHelperService::class, function (){
            return new ApiHelperService();
        });
    }
}
