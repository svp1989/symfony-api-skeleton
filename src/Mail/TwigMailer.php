<?php

namespace App\Mail;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\TwigSwiftMailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class TwigMailer
 * @package App\Mail
 */
class TwigMailer extends TwigSwiftMailer
{
    protected $template;

    protected $confirmationRoute;

    protected $resettingRoute;

    protected $context;

    /**
     * @param TwigSwiftMailer $mailer
     * @return TwigMailer
     */
    public static function fromParent(TwigSwiftMailer $mailer): TwigMailer
    {
        $class = get_called_class();
        return new $class(
            $mailer->mailer,
            $mailer->router,
            $mailer->twig,
            $mailer->parameters
        );
    }

    /**
     * @param \Swift_Mailer $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment $twig
     * @param array $parameters
     */
    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, array $parameters = array(), array $context = array())
    {
        parent::__construct($mailer, $router, $twig, $parameters);
        $this->context = $context;
        $this->confirmationRoute = '/{token}';
        $this->template = 'emails/base.html.twig';
    }

    /**
     * @param array $context
     * @return self
     */
    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param string $template
     * @return self;
     */
    public function setTemplate(string $template): self
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplatePath(string $template): string
    {
        return $this->template;
    }

    /**
     * @param string $confirmationRoute
     * @return self
     */
    public function setConfirmationRoute(string $confirmationRoute): self
    {
        $this->confirmationRoute = $confirmationRoute;
        return $this;
    }

    /*
     * @return string
     */
    public function getConfirmationRoute(): string {
        return $this->confirmationRoute;
    }

    /**
     * @param string $resettingRoute
     * @return self
     */
    public function setResettingRoute(string $resettingRoute): self
    {
        $this->resettingRoute = $resettingRoute;
        return $this;
    }

    /**
     * @return string
     */
    public function getResettingRoute(): string
    {
        return $this->resettingRoute;
    }

    public function getFromEmail(string $key): array
    {
        return $this->parameters['from_email'][$key];
    }

    /**
     * @param UserInterface $user
     * @return integer
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $token = $user->getConfirmationToken();
        $fromEmail = $this->getFromEmail('confirmation');
        $toEmail = $user->getEmail();

        $path = $this->router->generate(
            $this->getConfirmationRoute(),
            array('token' => $token),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $prepared = $this->renderEmailMessage(
            'Подтверждение E-mail',
            $fromEmail,
            $toEmail,
            $this->template,
            array(
                'username' => $user->getUsername(),
                'linkHref' => $path,
                'linkText' => explode('://', $path)[1]
            )
        );

        return $this->sendEmailMessage($prepared);
    }

    /**
     * @param UserInterface $user
     * @return int
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        $token = $user->getConfirmationToken();
        $fromEmail = $this->getFromEmail('resetting');
        $toEmail = $user->getEmail();

        $path = $this->router->generate(
            $this->getResettingRoute(),
            array('token' => $token),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $prepared = $this->renderEmailMessage(
            'Сброс пароля',
            $fromEmail,
            $toEmail,
            $this->template,
            array(
                'username' => $user->getUsername(),
                'linkHref' => $path,
                'linkText' => explode('://', $path)[1]
            )
        );

        return $this->sendEmailMessage($prepared);
    }

    /**
     * @param string $subject
     * @param array $from
     * @param string $to
     * @param string $template
     * @param array $context
     * @return PreparedEmail
     */
    public function renderEmailMessage(string $subject, array $from, string $to, string $template, array $context): PreparedEmail
    {
        $body = $this->twig->render($template, $context);
        return new PreparedEmail($subject, $from, $to, $body, 'text/html');
    }

    /**
     * @param PreparedEmail
     * @return int
     */
    public function sendEmailMessage(PreparedEmail $prepared)
    {
        $message = (new \Swift_Message($prepared->subject))
            ->setFrom($prepared->fromEmail)
            ->setTo($prepared->toEmail)
            ->setBody($prepared->tplBody, $prepared->tplType);

        return $this->mailer->send($message);
    }
}
