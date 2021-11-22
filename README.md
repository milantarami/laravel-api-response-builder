# Laravel API Response Builder

Installation
```
composer require milantarami/laravel-api-response-builder
```

Simple Usage
```
use MilanTarami\ApiResponseBuilder\Facades\ResponseBuilder;

$data = [1, 2, 3, 4];
return ResponseBuilder::success($data);

// output
{
    success: true,
    code: 200,
    message: "OK",
    data: [
        "1",
        "2",
        "3",
        "4"
    ]
}
```

wait for more extensive documentation.
