<?php
declare(strict_types=1);
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource()
 */
class Post
{
    use IdTrait;
    use TimestampTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $description;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Post
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param string $name
     * @return Post
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
     * @param string $description
     * @return Post
     */
    public function setDescription(string $description):self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
