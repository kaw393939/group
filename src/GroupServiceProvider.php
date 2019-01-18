<?php

namespace Kaw393939\Group;
use Kaw393939\Group\Models\Group;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class GroupServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(\Laratrust\LaratrustServiceProvider::class);
        Route::model('GroupResource', Group::class);

        //removes the laratrust default config
        $this->app['config']->set('laratrust', []);
        $this->mergeConfigFrom(
            __DIR__.'/../config/group.php',
            'laratrust'
        );

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('group', function ($app) {
            return new Group();
        });
    }
}
