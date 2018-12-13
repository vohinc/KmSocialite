<?php

namespace Voh\KmSocialite;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

/**
 * Class KmSocialiteServiceProvider
 *
 * @package Voh\KmSocialite
 */
class KmSocialiteServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot()
    {
        $socialite = $this->app->make(Factory::class);
        $socialite->extend('km', function ($app) use ($socialite) {
            $config = $app['config']['services.km'];

            return $socialite->buildProvider(KmSocialiteProvider::class, $config);
        });
    }
}
