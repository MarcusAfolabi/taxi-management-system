<?php

namespace App\Providers;

use App\Events\UserRegistered;
use Illuminate\Support\ServiceProvider;
use App\Listeners\SendUserRegisteredNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $listen = [
        UserRegistered::class => [
            SendUserRegisteredNotification::class,
        ],
    ];

    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
