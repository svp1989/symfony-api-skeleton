<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *     attributes={
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "normalization_context"={
 *                 "groups"={"get_users"},
 *                 "datetime_format"="d.m.Y H:i:s"
 *          }
 *     }
 * )
 */
class User extends BaseUser
{
    use IdTrait;
    use TimestampTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_users"})
     */
    protected $id;

    /**
     * @Groups({"get_users"})
     */
    protected $username;

    /**
     * @Groups({"get_users"})
     */
    protected $email;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Profile", inversedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     * @Groups({"get_users"})
     */
    private $profile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="user")
     */
    private $posts;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @Groups({"get_users"})
     */
    protected $createdAt;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        parent::__construct();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    /**
     * @return array|null
     */
    public function getPosts():? array
    {
        return $this->posts->toArray();
    }

    /**
     * @param Profile|null $profile
     * @return User
     */
    public function setProfile(Profile $profile = null): self
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @return Profile|null
     */
    public function getProfile():? Profile
    {
        return $this->profile;
    }
}
