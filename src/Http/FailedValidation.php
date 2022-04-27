<?php

namespace MilanTarami\ApiResponseBuilder\Http;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use MilanTarami\ApiResponseBuilder\Facades\ResponseBuilder;

trait FailedValidation
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = ResponseBuilder::asError(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->append('message', 'The given data is invalid')
            ->append('errors', $validator->errors())
            ->build();

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
