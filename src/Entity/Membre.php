<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MembreRepository")
 */
class Membre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $prenom;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Role")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdRole;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_embauchement;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_resignation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $created_by;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $edited_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $edited_by;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Equipe", mappedBy="membres")
     */
    private $equipes;

    public function __construct()
    {
        $this->equipes = new ArrayCollection();
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getIdRole(): ?Role
    {
        return $this->IdRole;
    }

    public function setIdRole(Role $IdRole): self
    {
        $this->IdRole = $IdRole;

        return $this;
    }

    public function getDateEmbauchement(): ?\DateTimeInterface
    {
        return $this->date_embauchement;
    }

    public function setDateEmbauchement(\DateTimeInterface $date_embauchement): self
    {
        $this->date_embauchement = $date_embauchement;

        return $this;
    }

    public function getDateResignation(): ?\DateTimeInterface
    {
        return $this->date_resignation;
    }

    public function setDateResignation(?\DateTimeInterface $date_resignation): self
    {
        $this->date_resignation = $date_resignation;

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

    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    public function setCreatedBy(int $created_by): self
    {
        $this->created_by = $created_by;

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

    public function getEditedBy(): ?\DateTimeInterface
    {
        return $this->edited_by;
    }

    public function setEditedBy(?\DateTimeInterface $edited_by): self
    {
        $this->edited_by = $edited_by;

        return $this;
    }

    /**
     * @return Collection|Equipe[]
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipe $equipe): self
    {
        if (!$this->equipes->contains($equipe)) {
            $this->equipes[] = $equipe;
            $equipe->addMembre($this);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): self
    {
        if ($this->equipes->contains($equipe)) {
            $this->equipes->removeElement($equipe);
            $equipe->removeMembre($this);
        }

        return $this;
    }

}
