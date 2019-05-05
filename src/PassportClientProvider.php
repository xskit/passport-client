<?php

namespace XsPkg\PassportClient;

use Illuminate\Support\ServiceProvider;

class PassportClientProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton(PassportClient::class);
    }
}