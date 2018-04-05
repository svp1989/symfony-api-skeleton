<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Response
 * @package App\Utils
 */
class Response
{
    /**
     * @param int $httpCode
     * @param string $message
     * @return JsonResponse
     */
    public static function toJson(int $httpCode, $message = 'success'): JsonResponse
    {
        return new JsonResponse([
            'code' => $httpCode,
            'message' => $message
        ], $httpCode);
    }
}