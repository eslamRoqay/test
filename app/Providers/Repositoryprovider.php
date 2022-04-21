<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class Repositoryprovider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repository\UserRepo',
            'App\RepositoryEloquent\UserRepoEleq'
        );
        $this->app->bind(
            'App\Repository\ShiftRepo',
            'App\RepositoryEloquent\ShiftRepoEleq'
        );
        $this->app->bind(
            'App\Repository\AdminRepo',
            'App\RepositoryEloquent\AdminRepoEleq'
        );
        $this->app->bind(
            'App\Repository\PharmacyRepo',
            'App\RepositoryEloquent\PharmacyRepoEleq'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
