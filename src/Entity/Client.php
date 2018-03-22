<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *     attributes={
 *          "normalization_context"={"groups"={"client"}}
 *     }
 * )
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"client"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="client")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"client"})
     */
    private $phone;
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"client"})
     */
    private $email;
    /**
     * @ORM\Column(type="string", name="first_name")
     * @Groups({"client"})
     */
    private $firstName;
    /**
     * @ORM\Column(type="string", name="last_name")
     * @Groups({"client"})
     */
    private $lastName;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"client"})
     */
    private $patronymic;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"client"})
     */
    private $citizenship;
    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"client"})
     */
    private $document;
    /**
     * @ORM\Column(type="string")
     * @Groups({"client"})
     */
    private $number;
    /**
     * @ORM\Column(type="datetime")
     * @Groups({"client"})
     */
    private $birthday;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->setCreatedAt(new \DateTime());
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param User $user
     * @return Client
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser():? User
    {
        return $this->user;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $email
     * @return Client
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $firstName
     * @return Client
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     * @return Client
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $patronymic
     * @return Client
     */
    public function setPatronymic(string $patronymic): self
    {
        $this->patronymic = $patronymic;
        return $this;
    }

    /**
     * @return string
     */
    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    /**
     * @param string $citizenship
     * @return Client
     */
    public function setCitizenship(string $citizenship): self
    {
        $this->citizenship = $citizenship;
        return $this;
    }

    /**
     * @return string
     */
    public function getCitizenship(): string
    {
        return $this->citizenship;
    }

    /**
     * @param string $document
     * @return Client
     */
    public function setDocument(string $document): self
    {
        $this->document = $document;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocument(): string
    {
        return $this->document;
    }

    /**
     * @param string $number
     * @return Client
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param \DateTime $birthday
     * @return Client
     */
    public function setBirthday(\DateTime $birthday): self
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday(): \DateTime
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $createdAt
     * @return Client
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
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
     * @param \DateTime $updatedAt
     * @return Client
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
