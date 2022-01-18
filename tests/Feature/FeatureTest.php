<?php

use MilanTarami\ApiResponseBuilder\Facades\ResponseBuilder;
use Orchestra\Testbench\TestCase;

class FeatureTest extends TestCase
{

    // When testing inside of a Laravel installation, this is not needed
    protected function getPackageProviders($app)
    {
        return [
            'MilanTarami\ApiResponseBuilder\ResponseBuilderServiceProvider'
        ];
    }

    /** @test */
    public function response_builder_should_return_success_response()
    {
        $data = [1, 2, 3, 4];

        $response = ResponseBuilder::asSuccess()
            ->withData($data)
            ->withMessage('message', [], null)
            ->build();

        $response = $response->getContent();

        $responseData = json_decode($response, true)['data'];

        $this->assertTrue($data === $responseData);
    }
}
