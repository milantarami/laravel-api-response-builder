<?php

namespace MilanTarami\ApiResponseBuilder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class ResponseBuilder.
 */
class ResponseBuilder extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'response_builder';
    }
}
