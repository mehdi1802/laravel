<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Laravel 5.4 made a change to the default
         *  database character set, and it’s now utf8mb4
         *  which includes support for storing emojis.
         * This only affects new applications and 
         * as long as you are running MySQL v5.7.7 and
         *  higher you do not need to do anything
         */
        Schema::defaultStringLength(191);
    }
}
