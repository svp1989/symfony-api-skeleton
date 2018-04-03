<?php

namespace App\Controller;

use App\Controller\Security\SecurityResetting;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

/**
 * Class SecurityResettingController
 * Resetting Password
 * @package App\Controller
 */
class SecurityResettingController extends Controller implements SecurityResetting
{
    /**
     * Check user email for resetting password
     * @SWG\Tag(
     *      name="Resetting"
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
     *      response=404,
     *      description="E-mail not exists",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @Route("/resetting/check-email", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getResettingCheckEmailAction(Request $request): JsonResponse
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

        $repo = $this->getDoctrine()->getRepository('App:User');

        if (!$repo->findOneBy(array('email' => $email))) {
            return new JsonResponse(array(
                'code' => 404,
                'message' => 'E-mail not exists'
            ), 404);
        }

        return new JsonResponse(array('success' => 'ok'));
    }

    /**
     * Rest password
     * @SWG\Tag(
     *      name="Resetting"
     * )
     * @SWG\Parameter(
     *      name="token",
     *      in="path",
     *      required=true,
     *      type="string",
     *      description="Confirmation token"
     * )
     * @SWG\Response(
     *      response=200,
     *      description="Passwod changed",
     *      @SWG\Schema(
     *          type="object",
     *          example={"success": "ok"},
     *          @SWG\Property(property="success", type="string", description="Password changed")
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
     * @Route("/resetting/reset/{token}", methods={"GET"})
     * @param string $token
     * @return JsonResponse
     */
    public function getResettingResetAction(string $token): JsonResponse
    {
        $user = $this->getDoctrine()
            ->getRepository('App:User')
            ->findOneBy(array('confirmationToken' => $token));

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Invalid E-mail token');
        }

        $tokenTtl = $this->container->getParameter('fos_user.resetting.token_ttl');
        $expired = !$user->isPasswordRequestNonExpired($tokenTtl);

        if ($expired) {
            throw new CustomUserMessageAuthenticationException('E-mail token expired');
        } else {
            return new JsonResponse(array('success' => 'ok'));
        }
    }

    /**
     * Set new password
     * @SWG\Tag(
     *      name="Resetting"
     * )
     * @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      required=true,
     *      description="New password",
     *      @SWG\Schema(
     *          type="object",
     *          example={"password": "password"}
     *      )
     * )
     * @SWG\Response(
     *      response=200,
     *      description="Password changed",
     *      @SWG\Schema(
     *          type="object",
     *          example={"success": "ok"},
     *          @SWG\Property(property="success", type="string", description="Password changed")
     *      )
     * )
     * @SWG\Response(
     *      response=400,
     *      description="Password is empty or resetting not confirmed",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
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
     * @Route("/resetting/password/{token}", methods={"POST"})
     * @param Request $request
     * @param string $token
     * @return JsonResponse
     */
    public function postResettingPasswordAction(Request $request, string $token): JsonResponse
    {
        $repo = $this->getDoctrine()->getRepository('App:User');
        $user = $repo->findOneBy(array('confirmationToken' => $token));

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Invalid e-mail token');
        }

        $tokenTtl = $this->container->getParameter('fos_user.resetting.token_ttl');
        $expired = !$user->isPasswordRequestNonExpired($tokenTtl);

        if ($expired) {
            throw new CustomUserMessageAuthenticationException('E-mail token expired');
        }

        $props = json_decode($request->getContent(), true);

        if (!isset($props['password']) or !$props['password']) {
            return new JsonResponse(array(
                'code' => 400,
                'message' => 'Password required'
            ), 400);
        }

        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($user, $props['password']);

        $user->setPassword($password);
        $user->setConfirmationToken(null);
        $user->setPasswordRequestedAt(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse(array('success' => 'ok'));
    }

    /**
     * Send resetting e-mail
     * @SWG\Tag(
     *      name="Resetting"
     * )
     * @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      required=true,
     *      description="User e-mail",
     *      @SWG\Schema(
     *          type="object",
     *          example={"email": "email"}
     *      )
     * )
     * @SWG\Response(
     *      response=200,
     *      description="Resetting e-mail sent",
     *      @SWG\Schema(
     *          type="object",
     *          example={"success": "ok"},
     *          @SWG\Property(property="success", type="string", description="Reseting e-mail sent")
     *      )
     * )
     * @SWG\Response(
     *      response=400,
     *      description="E-mail is empty or has invalid format",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @SWG\Response(
     *      response=404,
     *      description="E-mail not exists",
     *      @SWG\Schema(
     *          type="object",
     *          example={"code": "code", "message": "message"},
     *          @SWG\Property(property="code", type="integer", description="Http status code"),
     *          @SWG\Property(property="message", type="string", description="Error message")
     *      )
     * )
     * @Route("/resetting/send-email", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function postResettingSendEmailAction(Request $request): JsonResponse
    {
        $props = json_decode($request->getContent(), true);

        if (!isset($props['email']) or !$props['email']) {
            return new JsonResponse(array(
                'code' => 400,
                'message' => 'E-mail required'
            ), 400);
        }

        $email = (string)$props['email'];
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

        $repo = $this->getDoctrine()->getRepository('App:User');

        $user = $repo->findOneBy(array(
            'email' => $email,
            'enabled' => true
        ));

        if (!$user)
        {
            return new JsonResponse(array(
                'code' => 404,
                'message' => 'E-mail not exists'
            ), 404);
        }

        $tokenGenerator = $this->container->get('token_generator');
        $user->setConfirmationToken($tokenGenerator->generateToken());
        $user->setPasswordRequestedAt(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();


        return new JsonResponse(array('success' => 'ok'));
    }
}
