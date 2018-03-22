<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvalidTokenRepository")
 * @ApiResource()
 */
class InvalidToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $expiration;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $hash;

    function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $expiration
     * @return InvalidToken
     */
    function setExpiration(int $expiration): self
    {
        $this->expiration = $expiration;
        return $this;

    }

    /**
     * @return int
     */
    function getExpiration(): int
    {
        return $this->expiration;
    }

    /**
     * @param string $srcString
     * @return InvalidToken
     */
    function setHash(string $srcString): self
    {
        $this->hash = hash('sha256', $srcString, false);
        return $this;
    }

    /**
     * @return string
     */
    function getHash(): string
    {
        return $this->hash;
    }
}
