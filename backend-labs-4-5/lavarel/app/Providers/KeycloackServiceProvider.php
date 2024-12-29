<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class KeycloakServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Nothing to register
    }

    public function boot()
    {
        // Only extend Socialite for now
        Socialite::extend('keycloak', function ($app) {
            $config = $app['config']['services.keycloak'];
            
            return Socialite::buildProvider(
                \App\Services\KeycloakProvider::class,
                $config
            );
        });
    }
}