<?php

namespace MilanTarami\ApiResponseBuilder;

use Illuminate\Support\ServiceProvider;
use MilanTarami\ApiResponseBuilder\ResponseBuilder;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
