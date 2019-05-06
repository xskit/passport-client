<?php

namespace XsPkg\PassportClient;

use Illuminate\Support\ServiceProvider;

class PassportClientServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . 'config/passport_client.php' => config_path('passport_client.php'),
        ], 'passport-client-config');
    }

    public function register()
    {
        $this->app->singleton(PassportClient::class, function ($app) {
            return new PassportClient($app['config']['passport_client']);
        });
    }
}