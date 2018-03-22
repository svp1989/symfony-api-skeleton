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
     * GET /api/profile - user profile
     * @Rest\Route("/profile")
     * @param Request $request
     * @return JsonResponse
     */
    public function getProfileAction(Request $request);

    /**
     * PUT /api/profile/edit - edit user profile
     * @param Request $request
     * @return JsonResponse
     */
    public function putProfileEditAction(Request $request): JsonResponse;

    /**
     * PUT /api/profile/change-password - change password
     * @param Request $request
     * @Rest\Route("/profile/change-password")
     * @return JsonResponse
     */
    public function putChangePasswordAction(Request $request): JsonResponse;

    /**
     * GET /api/profile/confirm-email-update/{token} - confirm email address
     * @param $token string
     * @Rest\Route("/profile/confirm-email-update/{token}")
     * @return JsonResponse
     */
    public function getProfileConfirmEmailUpdateAction($token): JsonResponse;
}
