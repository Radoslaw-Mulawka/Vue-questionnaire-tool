<?php

namespace App\Providers;

use App\Laravue\Models\Campaign;
use App\Observers\CampaignObserver;
use App\Observers\UserObserver;
use App\Laravue\Models\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Campaign::observe(CampaignObserver::class);
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
