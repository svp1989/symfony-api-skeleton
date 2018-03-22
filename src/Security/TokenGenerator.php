<?php

namespace App\Security;

use FOS\UserBundle\Util\TokenGeneratorInterface;

class TokenGenerator
{
    protected $generator;

    public function __construct(TokenGeneratorInterface $tokenGenerator)
    {
        $this->generator = $tokenGenerator;
    }

    public function __call(string $method, array $args)
    {
        return call_user_func_array(array($this->generator, $method), $args);
    }
}
