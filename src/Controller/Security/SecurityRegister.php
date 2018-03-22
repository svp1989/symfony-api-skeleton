<?php

declare(strict_types = 1);

namespace App\Controller\Security;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Registration
 * Interface SecurityRegister
 * @package App\Controller\Security
 */
interface SecurityRegister
{

    /**
     * POST /api/register - user registration
     * @Rest\Route("/register")
     * @param Request $request
     * @return JsonResponse
     */
    public function postRegisterAction(Request $request): JsonResponse;

    /**
     * GET /api/register/check-email - check email address
     * @param Request $request
     * @Rest\Route("/register/check-email")
     * @return JsonResponse
     */
    public function getRegisterCheckEmailAction(Request $request): JsonResponse;

    /**
     * GET /api/register/check-username - check username
     * @param Request $request
     * @Rest\Route("/register/check-username)
     * @return JsonResponse
     */
    public function getRegisterCheckUsernameAction(Request $request): JsonResponse;

    /**
     * GET /api/register/confirm/{token} - confirm registration
     * @Rest\Route("/register/confirm/{token})
     * @param $token string
     * @return JsonResponse
     */
    public function getRegisterConfirmAction(string $token): JsonResponse;
}
