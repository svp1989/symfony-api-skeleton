<?php

declare(strict_types = 1);

namespace App\Controller\Security;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Password resetting
 * Interface SecurityResetting
 * @package App\Controller
 */
interface SecurityResetting
{
    /**
     * GET /api/resetting/send-email - send resetting token on email
     * @param Request $request
     * @Rest\Route("/resetting/send-email")
     * @return JsonResponse
     */
    public function postResettingSendEmailAction(Request $request): JsonResponse;

    /**
     * GET /api/resetting/check-email - check valid email
     * @param Request $request
     * @Rest\Route("/resetting/check-email")
     * @return JsonResponse
     */
    public function getResettingCheckEmailAction(Request $request): JsonResponse;

    /**
     * GET /api/resetting/reset - resetting email
     * @Rest\Route("/resetting/reset/{token}")
     * @param string $token
     * @return JsonResponse
     */
    public function getResettingResetAction(string $token): JsonResponse;
}
