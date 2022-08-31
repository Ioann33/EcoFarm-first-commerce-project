<?php

namespace App\Providers;

use App\Services\GetNameService;
use Illuminate\Support\ServiceProvider;

class GetNameProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->bind(GetNameService::class, function ($app){
//            return new GetNameService();
//        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('getName', GetNameService::class);
    }
}
