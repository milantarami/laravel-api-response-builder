<div id="getting-started"></div>

## Installation & Configuration

You can install this package via composer using:

```bash
    composer require milantarami/laravel-api-response-builder
```

The package will automatically register its service provider for laravel 5.5.\* and above. <br>
For below version need to register a service provider manually in <code>config/app.php</code>

```bash
'providers' => [

        /*
        * Package Service Providers...
        */

        MilanTarami\ApiResponseBuilder\ResponseBuilderServiceProvider::class
]
```

The package will automatically load alias for laravel 5.5.\* and above. <br>
For below version need to add alias manually in <code>config/app.php</code>

```bash
'aliases' => [
        .
        .
    'ResponseBuilder' => MilanTarami\ApiResponseBuilder\Facades\ResponseBuilder::class,

]
```

To publish the config file to <code>config/laravel-api-response-builder.php</code> run:

```bash
php artisan vendor:publish --tag=laravel-api-response-builder-config
```

## Example 1

> Simple Usage

```bash
use MilanTarami\ApiResponseBuilder\Facades\ResponseBuilder;

$data = [1, 2, 3, 4];
return ResponseBuilder::success($data);

// output
{
    code: 200,
    success: true,
    message: "OK",
    data: [
        "1",
        "2",
        "3",
        "4"
    ]
}
```

## Example 2

> Usage with chaining methods

```bash

use MilanTarami\ApiResponseBuilder\Facades\ResponseBuilder;


$todos = Todo::paginate(10);

return ResponseBuilder::asSuccess()
    ->withData($todos, TodoResource::class)
    ->withHttpCode(200)
    ->withMessage('Todos fetched successfully')
    ->append('custom-key', 'custom-value')
    ->build();


        // output
{
    success: true,
    code: 200,
    message: "Todos fetched successfully",
    data: {
        items: [
            {
                id: 1,
                title: "Beatae sapiente ab itaque dolores quis at.",
                description: "Vel tempora voluptate expedita ex. Fugiat qui nisi possimus ..."
            },
            {
                id: 2,
                title: "Distinctio deserunt omnis ut sint corrupti.",
                description: "Et architecto voluptatibus minus. Eius enim praesentium non sint dolorem in vero. Qui reiciendis ..."
            }
        ],
        total: 2,
        count: 2,
        per_page: 10,
        current_page: 1,
        total_pages: 1
    },
    custom-key: "custom-value"
}

```

## Example 3

> Customizing FormRequest JSON Response

```bash

    use  MilanTarami\ApiResponseBuilder\Http\FailedValidation;


    class StoreTodoRequest extends Request {

        use FailedValidation;
        .
        .

    }


    // output
    {
        "success": false,
        "code": 422,
        "message": "The given data is invalid",
        "errors": {
            "title": [
                "The title field is required."
            ]
        }
    }

```

## Exposed Methods

<table width="100">
    <thead>
        <tr>
            <th>Method Name</th>
            <th style="width: 350px;">Parameter</th>
            <th>Return Tyoe</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>success</td>
            <td>
                <li>@param <i>mixed</i> &nbsp;$resource</li>
                <li>@param <i>string</i> &nbsp;$message</li>
                <li>@param <i>int</i> &nbsp;$code</li>
                <li>@param <i>string</i>  &nbsp;$resourceNamespace</li>
                <li>@param <i>array</i>  &nbsp;$appends</li>
            </td>
            <td>
               <li>@return <i>\Illuminate\Http\JsonResponse</i> </li>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>error</td>
            <td>
                <li>@param <i>mixed</i> &nbsp;$data</li>
                <li>@param <i>string</i> &nbsp;$message</li>
                <li>@param <i>int</i> &nbsp;$code</li>
                <li>@param <i>array</i>  &nbsp;$appends</li>
            </td>
            <td>
               <li>@return <i>\Illuminate\Http\JsonResponse</i> </li>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>asSuccess</td>
            <td>
                <li>@param <i>int</i> &nbsp;$code</li>
            </td>
            <td>
               <li>@return <i>\MilanTarami\ApiResponseBuilder\ResponseBuilder</i> </li>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>asError</td>
            <td>
                <li>@param <i>int</i> &nbsp;$code</li>
            </td>
            <td>
               <li>@return <i>\MilanTarami\ApiResponseBuilder\ResponseBuilder</i> </li>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>withMessage</td>
            <td>
                <li>@param <i>string</i> &nbsp;$messageOrLocaleKey</li>
                <li>@param <i>array</i> &nbsp;$replace</li>
                <li>@param <i>string</i> &nbsp;$locale</li>
            </td>
            <td>
               <li>@return <i>\MilanTarami\ApiResponseBuilder\ResponseBuilder</i> </li>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>withHttpHeaders</td>
            <td>
                <li>@param <i>int</i> &nbsp;$headers</li>
            </td>
            <td>
               <li>@return <i>\MilanTarami\ApiResponseBuilder\ResponseBuilder</i> </li>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>withHttpCode</td>
            <td>
                <li>@param <i>int</i> &nbsp;$code</li>
            </td>
            <td>
               <li>@return <i>\MilanTarami\ApiResponseBuilder\ResponseBuilder</i> </li>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>withData</td>
            <td>
                <li>@param <i>mixed</i> &nbsp;$code</li>
            </td>
            <td>
               <li>@return <i>\MilanTarami\ApiResponseBuilder\ResponseBuilder</i> </li>
            </td>
            <td></td>
        </tr>
        <tr>
            <td>append</td>
            <td>
                <li>@param <i>string</i> &nbsp;$key</li>
                <li>@param <i>mixed</i> &nbsp;$value</li>
            </td>
            <td>
               <li>@return <i>\MilanTarami\ApiResponseBuilder\ResponseBuilder</i> </li>
            </td>
            <td></td>
        </tr>
        </tr>
        <tr>
            <td>build</td>
            <td></td>
            <td>
               <li>@return <i>\Illuminate\Http\JsonResponse</i> </li>
            </td>
            <td></td>
        </tr>
    </tbody>
</table>

Created By <a href="https://milantarami.com.np/" target="_blank">Milan Tarami</a> with 💖 love
