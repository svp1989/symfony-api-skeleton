<?php

namespace App\Controller;

use App\Controller\Security\SecurityRegister;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\EntityEditor;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Routing\Route;
use App\Utils\Constants;

/**
 * Class SecurityRegisterController
 * Registration
 * @package App\Controller
 */
class SecurityRegisterController extends FOSRestController implements SecurityRegister
{

    const REQUIRE_FIELD = [
        'username',
        'password',
        'email',
        'first_name',
        'last_name'
    ];

    /**
     * Check user email before registration
     * @SWG\Tag(
     *      name="Register"
     * )
     * @SWG\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      description="E-mail for checking",
     *      type="string"
     * )
     * @SWG\Response(
     *      response=200,
     *      description="User e-mail is valid",
     *      @SWG\Schema(
     *          type="object",
     *          example={"success": "ok"},
     *          @SWG\Property(property="success", type="string", description="User e-mail is valid")
     *      )
     * )
     * @SWG\Response(
     *      response=400,
     *      description="'E-mail required', 'Invalid e-mail format'",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @SWG\Response(
     *      response=409,
     *      description="E-mail exists",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @Rest\Route("/register/check-email", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getRegisterCheckEmailAction(Request $request): JsonResponse
    {
        $email = $request->query->get('email');

        if (!$email) {
            return new JsonResponse(array(
                'code' => 400,
                'message' => 'E-mail required'
            ), 400);
        }

        $emailConstraint = new EmailConstraint();
        $emailConstraint->message = $email;
        $validator = $this->container->get('validator');
        $errorCount = count($validator->validate($email, $emailConstraint));

        if ($errorCount != 0) {
            return new JsonResponse(array(
                'code' => 400,
                'message' => 'Invalid e-mail format'
            ), 400);
        }

        $user = $this->getDoctrine()->getRepository('App:User');

        if ($user->findOneBy(array('email' => $email))) {
            return new JsonResponse(array(
                'code' => 409,
                'message' => 'E-mail exists'
            ), 409);
        }

        return new JsonResponse(array('success' => 'ok'));
    }

    /**
     * Check user name before registration
     * @SWG\Tag(
     *      name="Register"
     * )
     * @SWG\Parameter(
     *      name="username",
     *      in="query",
     *      required=true,
     *      description="Username for checking",
     *      type="string"
     * )
     * @SWG\Response(
     *      response=200,
     *      description="Username is valid",
     *      @SWG\Schema(
     *          type="object",
     *          example={"success": "ok"},
     *          @SWG\Property(property="success", type="string", description="User name is not registered")
     *      )
     * )
     * @SWG\Response(
     *      response=400,
     *      description="Username required",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @SWG\Response(
     *      response=409,
     *      description="Username exists",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @Rest\Route("/register/check-username", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getRegisterCheckUsernameAction(Request $request): JsonResponse
    {
        $username = $request->query->get('username');

        if (!$username) {
            return new JsonResponse(array(
                'code' => 400,
                'message' => 'Username required'
            ), 400);
        }

        $repo = $this->getDoctrine()->getRepository('App:User');

        if ($repo->findOneBy(array('username' => $username))) {
            return new JsonResponse(array(
                'code' => 409,
                'message' => 'User exists'
            ), 409);
        }

        return new JsonResponse(array('success' => 'ok'));
    }

    /**
     * Confirm user registration
     * @SWG\Tag(
     *      name="Register"
     * )
     * @SWG\Parameter(
     *      name="token",
     *      in="path",
     *      required=true,
     *      type="string",
     *      description="Confirmation token",
     * )
     * @SWG\Response(
     *      response=200,
     *      description="E-mail confirmed",
     *      @SWG\Schema(
     *          type="object",
     *          example={"success": "ok"},
     *          @SWG\Property(property="success", type="string", description="E-mail confirmed")
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
     * @Rest\Route("/register/confirm/{token}", methods={"GET"})
     * @param string $token
     * @return JsonResponse
     */
    public function getRegisterConfirmAction(string $token): JsonResponse
    {
        $user = $this->getDoctrine()
            ->getRepository('App:User')
            ->findOneBy(array('confirmationToken' => $token));

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Invalid E-mail token');
        }

        if (!$user->isEnabled()) {
            $user->setConfirmationToken(NULL);
            $user->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return new JsonResponse(array('success' => 'ok'));
    }

    /**
     * Register new user
     * @SWG\Tag(
     *      name="Register"
     * )
     * @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      required=true,
     *      description="Registration data",
     *      @SWG\Schema(
     *          type="object",
     *          example={
     *              "username": "username",
     *              "email": "email",
     *              "password": "password",
     *              "first_name": "first_name",
     *              "last_name": "last_name",
     *              "client": 0
     *          }
     *      )
     * )
     * @SWG\Response(
     *      response=200,
     *      description="User registered",
     *      @SWG\Schema(
     *          type="object",
     *          example={"success": "ok"},
     *          @SWG\Property(property="success", type="string", description="User registered")
     *      )
     * )
     * @SWG\Response(
     *      response=400,
     *      description="'Required property', 'Invalid username', 'Invalid e-mail', 'Unknown client id'",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @SWG\Response(
     *      response=409,
     *      description="'User exists', 'Username exists', 'E-mail exists'",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @Rest\Route("/register", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function postRegisterAction(Request $request): JsonResponse
    {
        $props = json_decode($request->getContent(), true);
        $prop_keys = array_keys($props);

        foreach (self::REQUIRE_FIELD as $key) {
            if (!in_array($key, $prop_keys)) {
                return new JsonResponse(array(
                    'code' => 400,
                    'message' => 'Required property ' . $key
                ), 400);
            }
        }

        $userRepository = $this->getDoctrine()->getRepository('App:User');

        $user = $userRepository->findByUserAndEmail($props['username'], $props['email']);

        if ($user) {
            if ($user->isEnabled()) {
                return new JsonResponse(array(
                    'code' => 409,
                    'message' => 'User exists'
                ), 409);
            }
        } else {
            $userByName = $userRepository->findOneBy(array('username' => $props['username']));
            $userByEmail = $userRepository->findOneBy(array('email' => $props['email']));

            if ($userByName && $userByName->isEnabled()) {
                return new JsonResponse(array(
                    'code' => 409,
                    'message' => 'Username exists'
                ), 409);
            }

            if ($userByEmail && $userByEmail->isEnabled()) {
                return new JsonResponse(array(
                    'code' => 409,
                    'message' => 'E-mail exists'
                ), 409);
            }

            if ($userByEmail && ($userByEmail->getUsername() != $props['username'])) {
                return new JsonResponse(array(
                    'code' => 400,
                    'message' => 'Invalid username'
                ), 400);
            }

            if ($userByName && ($userByName->getEmail() != $props['email'])) {
                return new JsonResponse(array(
                    'code' => 400,
                    'message' => 'Invalid e-mail'
                ), 400);
            }

            $userManager = $this->container->get('user_manager');
            $user = $userManager->createUser();
        }

        $editable = array_merge(array(), self::REQUIRE_FIELD);
        unset($editable['password']);
        $editor = new EntityEditor($user, $editable);

        if ($editor->update($props)) {
            $encoder = $this->container->get('security.password_encoder');
            $password = $encoder->encodePassword($user, $props['password']);
            $user->setPassword($password);

            if (isset($props['client']) && $props['client']) {
                $client = $this->getDoctrine()
                    ->getRepository('App:Client')
                    ->findOneBy(array('id' => $props['client']));

                if (!$client) {
                    return new JsonResponse(array(
                        'code' => 400,
                        'message' => 'Unknown client id'
                    ), 400);
                }

                $user->setClient($client);
            }

            $tokenGenerator = $this->container->get('token_generator');

            if (!$user->isEnabled()) {
                $user->setConfirmationToken($tokenGenerator->generateToken());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

       /* if (!$user->isEnabled()) {
            //TODO: перенести в настройки
            $routes = $this->container->get('router')->getRouteCollection();
            $route = new Route('app_register_confirm');
            $routes->add('app_register_confirm', $route);

            $mailer = $this->container->get('mail_sender');

            $mailer->setTemplate('emails/register_confirm.html.twig')
                ->setConfirmationRoute(
                    'app_register_confirm'
                )
                ->sendConfirmationEmailMessage($user);
        }*/

        return new JsonResponse(array(
            'success' => 'ok'
        ));
    }

    /**
     * Check user is confirmed
     * @SWG\Tag(
     *      name="Register"
     * )
     * @SWG\Response(
     *      response=200,
     *      description="Registration is confirmed",
     *      @SWG\Schema(
     *          type="object",
     *          example={"confirmed": "confirmed"},
     *          @SWG\Property(property="success", type="string", description="Checking success"),
     *          @SWG\Property(property="confirmed", type="string", description="Registration is confirmed")
     *      )
     * )
     * @Rest\Route("/register/confirmed", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getRegisterConfirmedAction(Request $request): JsonResponse
    {
        $username = $request->query->get('username');

        if (!$username) {
            return new JsonResponse(array(
                'confirmed' => true
            ));
        }

        $repo = $this->getDoctrine()->getRepository('App:User');
        $user = $repo->findOneBy(array('username' => $username));

        if (!$user) {
            return new JsonResponse(array(
                'confirmed' => true
            ));
        }

        return new JsonResponse(array(
            'confirmed' => $user->getConfirmationToken() ? false : true
        ));
    }
}
