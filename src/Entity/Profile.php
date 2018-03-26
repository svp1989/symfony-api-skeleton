<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Profile
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
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="profile")
     */
    private $user;

    /**
     * @ORM\Column(type="string", name="first_name")
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $firstName;
    /**
     * @ORM\Column(type="string", name="last_name")
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $lastName;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Type(type="string")
     */
    private $patronymic;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $citizenship;
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $document;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    private $number;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $birthday;

    /**
     * @return User|null
     */
    public function getUser():? User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Profile
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }


    /**
     * @param string $firstName
     * @return Profile
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
     * @return Profile
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
     * @return Profile
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
     * @return Profile
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
     * @return Profile
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
     * @return Profile
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
     * @return Profile
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
}
