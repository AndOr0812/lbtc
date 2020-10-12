<?php

namespace Ndlovu28\Lbtc;

use Illuminate\Support\ServiceProvider;

class LbtcServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Ndlovu28\Lbtc\Lbtc');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            Ndlovu28\Lbtc\Commands\InstallCommand::class
        ]);
    }
}
