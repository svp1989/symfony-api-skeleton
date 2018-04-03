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
     * @param int $code
     * @param $data
     * @return JsonResponse
     */
    public static function json(int $code, $data = 'success'): JsonResponse
    {
        return new JsonResponse([
            'code' => $code,
            'message' => $data
        ], $code);
    }
}