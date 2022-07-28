<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\ResponseContract;
use App\Http\APIBaseResponder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ResponseContract::class, APIBaseResponder::class);
    }
}
