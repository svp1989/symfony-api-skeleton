<?php

declare(strict_types = 1);

namespace App\Controller\Security;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @Rest\Route("/login")
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
