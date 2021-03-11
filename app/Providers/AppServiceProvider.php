<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Pagination\Paginator::useBootstrap();

        /*
        * Guarded / fillable params are awkward to work with.
        *
        * Use Laravel's validator in requests & it'll only give you known & validated fields from POSTs/etc.
        * That solves the problem $fillable wanted to solve, without creating a dozen new problems.
        */
        \Illuminate\Database\Eloquent\Model::unguard();
    }
}
