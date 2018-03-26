<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilesRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource()
 */
class Files
{
    use IdTrait;
    use TimestampTrait;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="files")
     */
    private $owner;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $name;
    /**
     * @ORM\Column(type="string")
     */
    private $hash;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $description;
    /**
     * @ORM\Column(type="string")
     */
    private $type;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $code;
    /**
     * @ORM\Column(type="integer")
     */
    private $size;


    /**
     * @param User $owner ;
     * @return Files
     */
    public function setOwner(User $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @param int $code
     * @return Files
     */
    public function setCode(int $code = null): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCode():? int
    {
        return $this->code;
    }

    /**
     * @param string $name
     * @return Files
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $hash
     * @return Files
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $description
     * @return Files
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $type
     * @return Files
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param int $size
     * @return Files
     */
    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }
}
