<?php

namespace App\Providers;

use App\Brewery\BreweryClient;
use App\Brewery\BreweryContext;
use App\Brewery\Mapper\Mapper;
use App\Services\AuthService;
use App\Services\BreweryService;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuthService::class);
        $this->app->singleton(BreweryService::class);

        $this->app->bind(SerializerBuilder::class, function (Application $app) {

            return SerializerBuilder::create()->setSerializationContextFactory(function () {
                return SerializationContext::create()
                    ->setSerializeNull(true);
            })->addDefaultHandlers();
        });

        $this->app->singleton(Mapper::class, function (Application $app) {
            return new Mapper($app->get(SerializerBuilder::class));
        });

        $this->app->singleton('brewery.logger', function (Application $app) {
            return with(new Logger("brewery-client"))->pushHandler(
                new RotatingFileHandler(storage_path('logs/brewery-client.log'))
            );
        });

        $this->app->singleton(BreweryContext::class, function (Application $app) {

            return BreweryContext::create()
                ->withSerializerBuilder($app->get(SerializerBuilder::class))
                ->withMapper($app->get(Mapper::class))
                ->withLogger($app->get('brewery.logger'))
                ->build();
        });

        $this->app->singleton(BreweryClient::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RedirectIfAuthenticated::redirectUsing(function () {
            return route('homepage');
        });

        Blade::component('layout', 'public-layout');
    }

}
