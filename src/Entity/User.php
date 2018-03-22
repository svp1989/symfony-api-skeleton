<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Client", inversedBy="user")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", nullable=true)
     */
    private $client;

    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $patronymic;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;



    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param Client $client
     * @return User
     */
    public function setClient(Client $client = null): self
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Client
     */
    public function getClient():? Client
    {
        return $this->client;
    }

    /**
     * @param $firstName
     * @return User
     */
    public function setFirstName($firstName = null): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName():?string
    {
        return $this->firstName;
    }

    /**
     * @param $lastName
     * @return User
     */
    public function setLastName($lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName():? string
    {
        return $this->lastName;
    }

    /**
     * @param null $patronymic
     * @return User
     */
    public function setPatronymic($patronymic = null): self
    {
        $this->patronymic = $patronymic;
        return $this;
    }

    /**
     * @return string
     */
    public function getPatronymic():? string
    {
        return $this->patronymic;
    }

    /**
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return array
     */
    public function getProfile(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'patronymic' => $this->getPatronymic(),
            'role' => $this->getRoles()
        ];
    }
}
