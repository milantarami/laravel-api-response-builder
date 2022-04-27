<?php

namespace MilanTarami\ApiResponseBuilder;

use Illuminate\Support\ServiceProvider;

class ResponseBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('response_builder', ResponseBuilder::class);

        $this->mergeConfigFrom(__DIR__.'./../config/laravel-api-response-builder.php', 'laravel-api-response-builder');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePublishing();
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__.'./../config/laravel-api-response-builder.php' => config_path('laravel-api-response-builder.php'),
                ],
                'laravel-api-response-builder-config'
            );
        }
    }
}
