<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipeRepository")
 */
class Equipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $equipeName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Membre", inversedBy="equipes")
     */
    private $membres;

    /**
     * @ORM\Column(type="integer")
     */
    private $created_By;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $edited_at;

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

    public function getEquipeName(): ?string
    {
        return $this->equipeName;
    }

    public function setEquipeName(string $equipeName): self
    {
        $this->equipeName = $equipeName;

        return $this;
    }

    /**
     * @return Collection|Membre[]
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(Membre $membre): self
    {
        if (!$this->membres->contains($membre)) {
            $this->membres[] = $membre;
        }

        return $this;
    }

    public function removeMembre(Membre $membre): self
    {
        if ($this->membres->contains($membre)) {
            $this->membres->removeElement($membre);
        }

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getEditedAt(): ?\DateTimeInterface
    {
        return $this->edited_at;
    }

    public function setEditedAt(?\DateTimeInterface $edited_at): self
    {
        $this->edited_at = $edited_at;

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
