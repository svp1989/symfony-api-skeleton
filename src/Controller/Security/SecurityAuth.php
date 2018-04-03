<?php

declare(strict_types = 1);

namespace App\Controller\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Authorisation
 * Interface SecurityAuth
 * @package App\Controller\Security
 */
interface SecurityAuth
{
    /**
     * POST /api/login - user authorisation
     * @param Request $request
     * @Route("/login")
     * @return JsonResponse
     */
    public function postLoginAction(Request $request): JsonResponse;
    /**
     * POST /api/logout - invalid jwt token
     * @param Request $request
     * @return JsonResponse
     */
    public function getLogoutAction(Request $request): JsonResponse;
}
