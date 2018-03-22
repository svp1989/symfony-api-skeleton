<?php

namespace App\Mail;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\TwigSwiftMailer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Service to send mails
 *
 * $mailSender = $this->container->get('mail_sender');
 * $prepared = $mailSender->renderEmailMessage('Subject', array('from@example.com' => 'John Doe'), 'to@example.com', 'path/to/tempalte', array('templateVar' => 'value'));
 * $success = (bool)$mailSender->sendEmailMessage($prepared)
 */
class MailSender
{
    /**
     * @var TwigMailer
     */
    protected $mailer;

    protected $realTransport;

    protected $sendImmediate;

    /**
     * @param TwigSwiftMailer $mailer
     * @param \Swift_Transport $realTransport
     * @param boolean $sendImmediate
     */
    public function __construct(TwigSwiftMailer $mailer, \Swift_Transport $realTransport, bool $sendImmediate)
    {
        $this->mailer = TwigMailer::fromParent($mailer);
        $this->realTransport = $realTransport;
        $this->sendImmediate = $sendImmediate;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        return call_user_func_array(array($this->mailer, $method), $args);
    }

    public function flush(): void
    {
        $spool = $this->mailer->getTransport()->getSpool();
        $spool->flushQueue($this->realTransport);
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
        return $this->mailer->renderEmailMessage($subject, $from, $to, $template, $context);
    }

    /**
     * @param PreparedEmail $prepared
     * @return int
     */
    protected function sendEmailMessage(PreparedEmail $prepared): int
    {
        try {
            $sent = $this->mailer->sendEmailMessage($prepared);
            if ($this->sendImmediate) {
                $this->flush();
            }
            return $sent;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
