<?php

declare(strict_types = 1);

namespace App\Controller\Security;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * User profile
 * Interface SecurityProfile
 * @package App\Controller\Security
 */
interface SecurityProfile
{
    /**
     * PUT /api/profiles/change-password - change password
     * @param Request $request
     * @Rest\Route("/profiles/change-password")
     * @return JsonResponse
     */
    public function putChangePasswordAction(Request $request): JsonResponse;

    /**
     * GET /api/profiles/confirm-email-update/{token} - confirm email address
     * @param $token string
     * @Rest\Route("/profiles/confirm-email-update/{token}")
     * @return JsonResponse
     */
    public function getProfilesConfirmEmailUpdateAction($token): JsonResponse;
}
