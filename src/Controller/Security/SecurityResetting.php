<?php

declare(strict_types = 1);

namespace App\Controller\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/resetting/send-email")
     * @return JsonResponse
     */
    public function postResettingSendEmailAction(Request $request): JsonResponse;

    /**
     * GET /api/resetting/check-email - check valid email
     * @param Request $request
     * @Route("/resetting/check-email")
     * @return JsonResponse
     */
    public function getResettingCheckEmailAction(Request $request): JsonResponse;

    /**
     * GET /api/resetting/reset - resetting email
     * @Route("/resetting/reset/{token}")
     * @param string $token
     * @return JsonResponse
     */
    public function getResettingResetAction(string $token): JsonResponse;
}
