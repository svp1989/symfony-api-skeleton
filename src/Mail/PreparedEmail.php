<?php

namespace App\Mail;

/**
 * Class PreparedEmail
 * @package App\Mail
 */
class PreparedEmail
{
    public $subject;

    public $fromEmail;

    public $toEmail;

    public $tplBody;

    public $tplType;

    /**
     * @param string $subject
     * @param array $fromEmail
     * @param string $toEmail
     * @param string $tplBody
     * @param string $tplType
     */
    public function __construct(string $subject, array $fromEmail, string $toEmail, string $tplBody, string $tplType)
    {
        $this->subject = $subject;
        $this->fromEmail = $fromEmail;
        $this->toEmail = $toEmail;
        $this->tplBody = $tplBody;
        $this->tplType = $tplType;
    }
}
