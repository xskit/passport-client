<?php

namespace XsKit\PassportClient;

use Illuminate\Support\ServiceProvider;

class PassportClientServiceProvider extends ServiceProvider
{
    public function boot()
    {

        if (method_exists($this, 'publishes') && function_exists('config_path')) {
            $this->publishes([
                __DIR__ . '/config/passport_client.php' => config_path('passport_client.php'),
            ], 'passport-client-config');
        }

    }

    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            return new Client($app['config']['passport_client']);
        });
    }
}