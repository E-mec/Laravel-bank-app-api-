<?php

namespace App\Providers;

use App\Events\DepositEvent;
use App\Listeners\DepositListener;
use App\Listeners\WithdrawalListener;
use Illuminate\Support\Facades\Event;
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
        Event::listen(
            // DepositEvent::class,
            DepositListener::class,
            WithdrawalListener::class
        );
    }
}
