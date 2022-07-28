<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

interface ResponseContract
{
    /**
     * Success response method
     *
     * @param array $data
     * @param string $message
     * @param int $httpStatusCode
     *
     * @return JsonResponse
     */
    public function response(
        array $data,
        string $message = "",
        int $httpStatusCode = Response::HTTP_OK
    ): JsonResponse;

    /**
     * Error response method
     *
     * @param string $message
     * @param int $httpStatusCode
     * @param ?mixed $errors
     * @param ?mixed $data
     *
     * @return JsonResponse
     */
    public function error(
        string $message = "",
        int $httpStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        mixed $errors = null,
        mixed $data = null
    ): JsonResponse;
}
