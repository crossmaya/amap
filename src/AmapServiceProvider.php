<?php

namespace Jt\Amap;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class AmapServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Publish configuration files
        $this->publishes([
            __DIR__ . '/../config/amap.php' => config_path('amap.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/amap.php',
            'amap'
        );

        $this->registerAmapClient();
    }

    protected function registerAmapClient()
    {
        $this->app->singleton('amap.client', function (Container $app) {

            return new Client($app['config']['amap']);

        });

        $this->app->alias('amap.client', Client::class);
    }
}
