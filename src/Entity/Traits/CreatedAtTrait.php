<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{
    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return self
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->setCreatedAt(new \DateTime());
    }

}