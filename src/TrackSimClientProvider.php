<?php

namespace HuckinB\TrackSimClient;

use Illuminate\Support\ServiceProvider;
use HuckinB\TrackSimClient\Client;

class TrackSimClientProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/tracksim.php', 'tracksim');

        $this->app->bind(Client::class, function ($app) {
            return new Client(config('tracksim.apikey'));
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/tracksim.php' => config_path('tracksim.php'),
        ]);
    }

}