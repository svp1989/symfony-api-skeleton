<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Swagger\Annotations as SWG;

/**
 * Class ProfileController
 * Working with user profile
 * @package App\Controller
 */
class ProfileController extends Controller
{

    /**
     * Create user profile
     * @SWG\Tag(
     *      name="Profile"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Profile object",
     *     required=true,
     *     @SWG\Schema(
     *         example={
     *              "firstName":"string",
     *              "lastName":"string",
     *              "patronymic":"string",
     *              "citizenship":"string",
     *              "document":"string",
     *              "number":"string",
     *              "birthday":"2018-03-26T12:31:53.649Z",
     *         }
     *     )
     * )
     * @SWG\Response(
     *      response=200,
     *      description="User profile created",
     *      @SWG\Schema(
     *          type="object",
     *          example={"success": "ok"}
     *      )
     * )
     * @SWG\Response(
     *      response=401,
     *      description="Bad credentials",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @param Request $request
     * @Route("/profiles", methods={"POST"})
     * @return JsonResponse
     */
    public function postProfileAction(Request $request): JsonResponse
    {
        $profileService = $this->container->get('profile_service');
        $content = json_decode($request->getContent());
        $result = $profileService->create($content);
        if ($result === true) {
            return new JsonResponse(array('success' => 'ok'));
        }
        return new JsonResponse(array('error' => $result));

    }

    /**
     * Update user profile
     * @SWG\Tag(
     *      name="Profile"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Profile object",
     *     required=true,
     *     @SWG\Schema(
     *         example={
     *              "firstName":"string",
     *              "lastName":"string",
     *              "patronymic":"string",
     *              "citizenship":"string",
     *              "document":"string",
     *              "number":"string",
     *              "birthday":"2018-03-26T12:31:53.649Z",
     *         }
     *     )
     * )
     * @SWG\Response(
     *      response=200,
     *      description="User profile updated",
     *      @SWG\Schema(
     *          type="object",
     *          example={"success": "ok"}
     *      )
     * )
     * @SWG\Response(
     *      response=401,
     *      description="Bad credentials",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @param Request $request
     * @Route("/profiles", methods={"PUT"})
     * @return JsonResponse
     */
    public function putProfileAction(Request $request): JsonResponse
    {
        $profileService = $this->container->get('profile_service');
        $content = json_decode($request->getContent());
        $result = $profileService->update($content);
        if ($result === true) {
            return new JsonResponse(array('success' => 'ok'));
        }
        return new JsonResponse(array('error' => $result));

    }

    /**
     * Get user profile
     * @SWG\Tag(
     *      name="Profile"
     * )
     * @SWG\Response(
     *      response=200,
     *      description="User profile",
     *      @SWG\Schema(
     *          type="object",
     *          example={
     *              "id": "integer",
     *              "username":"string",
     *              "email": "string",
     *              "profile": {
     *                  "firstName":"string",
     *                  "lastName":"string",
     *                  "patronymic":"string",
     *                  "citizenship":"string",
     *                  "document":"string",
     *                  "number":"string",
     *                  "birthday":"2018-03-26T12:31:53.649Z",
     *               }
     *          }
     *      )
     * )
     * @SWG\Response(
     *      response=401,
     *      description="Bad credentials",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @param $request Request
     * @Route("/profiles", methods={"GET"})
     * @return JsonResponse
     */
    public function getProfilesAction(Request $request): JsonResponse
    {
        $authToken = $this->container->get('token_authenticator');
        $token = $authToken->getCredentials($request);
        $user = $authToken->getUser($token);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Invalid E-mail token');
        }

        $answer = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'profile' => []
        ];
        $profile = $user->getProfile();
        if (isset($profile)) {
            $answer['profile'] = [
                'firstName' => $profile->getFirstName(),
                'lastName' => $profile->getLastName(),
                'patronymic' => $profile->getPatronymic(),
                'citizenship' => $profile->getCitizenship(),
                'document' => $profile->getDocument(),
                'number' => $profile->getNumber(),
                'birthday' => date('d.m.Y', $profile->getBirthday()->getTimestamp()),

            ];
        }

        return new JsonResponse($answer);
    }
}
