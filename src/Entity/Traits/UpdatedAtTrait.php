<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait UpdatedAtTrait
{

    /**
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    protected $updatedAt;

    /**
     * @param \DateTime $updatedAt
     * @return self
     */
    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt():? \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void
    {
        $this->setUpdatedAt(new \DateTime());
    }
}