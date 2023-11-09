<?php

namespace App\Providers;

use App\Repositories\CourierTokenRepository;
use App\Repositories\Interfaces\CourierTokenRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CourierTokenRepositoryInterface::class, CourierTokenRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
