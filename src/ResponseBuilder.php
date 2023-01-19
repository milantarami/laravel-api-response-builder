<?php

namespace MilanTarami\ApiResponseBuilder;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use MilanTarami\ApiResponseBuilder\Exception\InvalidArrayArgumentException;
use Symfony\Component\HttpFoundation\Response;

class ResponseBuilder
{
    /** @var int */
    private $code;

    /** @var string */
    private $message;

    /** @var bool */
    private $success;

    /** @var array */
    private $httpHeaders = [];

    /** @var mixed */
    private $data = null;

    /** @var array */
    private $response = [];

    /** @var array */
    private $appends = [];

    public function __construct(bool $success = true, int $code = Response::HTTP_OK, string $message = 'OK')
    {
        $this->success = $success;
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * @param  int  $code
     * @return App\ApiResponseBuilder\ResponseBuilder
     */
    public function asSuccess(?int $code = null)
    {
        return new static(true, $this->code ?? Response::HTTP_OK, 'OK');
    }

    /**
     * @param  int  $code
     * @return App\ApiResponseBuilder\ResponseBuilder
     */
    public function asError(?int $code = null)
    {
        return new static(false, $code ?? Response::HTTP_BAD_REQUEST, 'Error');
    }

    /**
     * @param  string  $message  - message to be returned or locale key
     * @param  array  $params  - params to be passed to the message
     * @param  string  $locale  - locale to be used
     * @return $this
     */
    public function withMessage(string $message, $replace = [], $locale = null)
    {
        $this->message = trans($message, $replace, $locale ?? config('laravel-api-response-builder.locale') ?? app()->getLocale());

        return $this;
    }

    /**
     * @param  array  $headers
     * @return $this
     */
    public function withHttpHeaders(?array $headers): self
    {
        $this->httpHeaders = $headers;

        return $this;
    }

    /**
     * @param  int  $code
     * @return $this
     */
    public function withHttpCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param  mixed  $resource
     * @param  string|null  $resourceNamespace
     * @return $this
     */
    public function withData($resource, ?string $resourceNamespace = null): self
    {
        $data = $resource;

        if (! empty($resourceNamespace)) {
            $data =
                $resource instanceof LengthAwarePaginator ||
                $resource instanceof Collection
                ? $resourceNamespace::collection($resource)
                : $resourceNamespace::make($resource);
        } else {
            $data =
                $resource instanceof LengthAwarePaginator
                ? $resource->items()
                : $resource;
        }

        if ($resource instanceof LengthAwarePaginator) {
            $this->append('meta', [
                "previous" => $resource->previousPageUrl(),
                "next" => $resource->nextPageUrl(),
                "total" => $resource->total(),
                "count" => $resource->count(),
                "per_page" => $resource->perPage(),
                "current_page" => $resource->currentPage(),
                "total_pages" => $resource->lastPage(),
            ]);
        }

        if (! empty($data)) {
            $this->data = $data;
        }

        return $this;
    }

    /**
     * @return Illuminate\Http\JsonResponse
     */
    public function build()
    {
        $this->response['success'] = $this->success;
        $this->response['code'] = $this->code;
        $this->response['message'] = $this->message;

        if (! empty($this->data)) {
            $this->response['data'] = $this->data;
        }

        if (! empty($this->appends)) {
            $this->response = array_merge($this->response, $this->appends);
        }

        return response()->json(
            $this->response,
            $this->code,
            $this->httpHeaders
        );
    }

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function append($key, $value)
    {
        $this->appends[$key] = $value;

        return $this;
    }

    /**
     * Api Failed Response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($data, $message = null, $code = Response::HTTP_BAD_REQUEST, array $appends = [])
    {
        if (! empty($appends) && ! Arr::isAssoc($appends)) {
            throw new InvalidArrayArgumentException('Appends must be an associative array');
        }

        return (new static(false, $code, $message ?? 'Error'))
            ->when(! empty($data), function (ResponseBuilder $builder) use ($data) {
                return $builder->withData($data);
            })
            ->when(! empty($code), function (ResponseBuilder $builder) use ($code) {
                return $builder->withHttpCode($code);
            })
            ->when(! empty($message), function (ResponseBuilder $builder) use ($message) {
                return $builder->withMessage($message);
            })
            ->when(! empty($appends), function (ResponseBuilder $builder) use ($appends) {
                foreach ($appends as $key => $value) {
                    $builder->append($key, $value);
                }

                return $builder;
            })
            ->build();
    }

    /**
     * Api Success Response.
     *
     * @param  mixed  $resource
     * @param  string  $message
     * @param  int  $code
     * @param  namespace  $resourceNamespace
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($resource, $message = null, $code = Response::HTTP_OK, $resourceNamespace = null, array $appends = [])
    {
        if (! empty($appends) && ! Arr::isAssoc($appends)) {
            throw new InvalidArrayArgumentException('Appends must be an associative array');
        }

        return (new static(true, $code, $message ?? 'OK'))
            ->when(! empty($resource), function (ResponseBuilder $builder) use ($resource, $resourceNamespace) {
                return $builder->withData($resource, $resourceNamespace);
            })
            ->when(! empty($code), function (ResponseBuilder $builder) use ($code) {
                return $builder->withHttpCode($code);
            })
            ->when(! empty($message), function (ResponseBuilder $builder) use ($message) {
                return $builder->withMessage($message);
            })
            ->when(! empty($appends), function (ResponseBuilder $builder) use ($appends) {
                foreach ($appends as $key => $value) {
                    $builder->append($key, $value);
                }

                return $builder;
            })
            ->build();
    }

    /**
     * @param  bool  $condition
     * @param  callable  $callback
     * @return $this
     */
    public function when(bool $condition, callable $callback)
    {
        if ($condition) {
            return $callback($this);
        }

        return $this;
    }
}
