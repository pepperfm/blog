<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use App\Contracts\ResponseContract;

class APIBaseResponder implements ResponseContract
{
    /** @var array|string[] $headers */
    private array $headers = ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'];

    /**
     * @inheritdoc
     */
    public function response(
        array $data,
        string $message = "",
        int $httpStatusCode = Response::HTTP_OK
    ): JsonResponse
    {
        $response = [
            'message' => $message,
            'errors'  => null,
            'data'    => $data,
        ];

        return new JsonResponse($response, $httpStatusCode, $this->headers, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @inheritdoc
     */
    public function error(
        string $message = "",
        int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        mixed $errors = null,
        mixed $data = null
    ): JsonResponse
    {
        $response = [
            'message' => $message,
            'errors'  => $errors,
            'data'    => $data,
        ];

        return new JsonResponse($response, $httpStatusCode, $this->headers, JSON_UNESCAPED_UNICODE);
    }
}
