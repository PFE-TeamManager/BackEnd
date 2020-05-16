<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 */
class Team
{
    use TimestampableEntity;//this to generate created_At and updated_At

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $teamName;

    /**
     * @ORM\Column(type="integer")
     */
    private $created_By;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $edited_by;

    public function __construct()
    {
        $this->membres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamName(): ?string
    {
        return $this->teamName;
    }

    public function setTeamName(string $teamName): self
    {
        $this->teamName = $teamName;

        return $this;
    }

    public function getCreatedBy(): ?int
    {
        return $this->created_By;
    }

    public function setCreatedBy(int $created_By): self
    {
        $this->created_By = $created_By;

        return $this;
    }

    public function getEditedBy(): ?int
    {
        return $this->edited_by;
    }

    public function setEditedBy(?int $edited_by): self
    {
        $this->edited_by = $edited_by;

        return $this;
    }
}
