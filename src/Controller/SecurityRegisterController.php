<?php

namespace App\Controller;

use App\Controller\Security\SecurityRegister;
use App\Entity\User;
use App\Utils\HttpCode;
use App\Utils\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

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
        'email'
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
     * @Route("/register/check-email", methods={"GET"})
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
     * @Route("/register/check-username", methods={"GET"})
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
     * @Route("/register/confirm/{token}", methods={"GET"})
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
     *              "password": "password"
     *          }
     *      )
     * )
     * @SWG\Response(
     *      response=200,
     *      description="User registered",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "200", "message": "success"},
     *          @SWG\Property(property="success", type="string", description="User registered")
     *      )
     * )
     * @SWG\Response(
     *      response=400,
     *      description="'Required property', 'Invalid username', 'Invalid e-mail'",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "400", "message": "errors"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="errors", type="string", description="Error message")
     *      )
     * )
     * @Route("/register", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function postRegisterAction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), false);
        $validator = $this->container->get('app_validator');

        $user = new User();
        $user->setUsername($data->username);
        $user->setEmail($data->email);
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, $data->password);
        $user->setPassword($password);

        $errors = $validator->toArray($user);

        if ($errors) {
            return Response::toJson(HttpCode::BAD_REQUEST, $errors);
        }

        $tokenGenerator = $this->container->get('token_generator');

        if (!$user->isEnabled()) {
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return Response::toJson(HttpCode::OK);
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
     * @Route("/register/confirmed", methods={"GET"})
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
