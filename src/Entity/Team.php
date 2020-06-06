<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Entity\Interfaces\CreatorEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\TeamsDatableAction;
use App\Controller\TeamsActivityAction;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// To find the correct operation name you may use bin/console debug:router
// To get the users insted of their URI , we used the subresourceOperations & 
// new name of the url of the subresource "api_teams_users_get_subresource"
// and we decided on wich context to return data (normalization_context), 
// then create the group of the properties we want to return, 
// include the group in this entity and the other entity wich contain the subressources

/**
* @ApiResource(
*           subresourceOperations={
*               "api_users_created_teams_get_subresource" = {
*                   "method"="GET",
*                   "normalization_context"={  "groups"={"get-Teams-Created-By-User"}  }
*               },
*               "api_teams_users_get_subresource" = {
*                   "method"="GET",
*                   "normalization_context"={  "groups"={"get-Users-Of-Team"}  }                        
*               }
*           },
*           collectionOperations={
*               "post"={
*                   "access_control"="is_granted('ROLE_CHEF_PROJET')",
*                   "denormalization_context"={ "groups"={"create-Team"} },
*                   "normalization_context"={  "groups"={"get-Teams-With-Projects"}  } 
*                },
*               "get"={
*                   "access_control"="is_granted('ROLE_CHEF_PROJET')",
*                   "method"="GET",
*                   "path"="/teamsdatatable",
*                   "controller"=TeamsDatableAction::class,
*                   "normalization_context"={  "groups"={"get-Teams-With-Projects"}  }
*                }
*           },
*           itemOperations={
*                "get",
*                "patch"={
*                    "access_control"="is_granted('ROLE_CHEF_PROJET')",
*                    "input_formats"={"json"={"application/json"}},
*                    "method"="PATCH",
*                    "path"="/teamsactivity/{id}",
*                    "controller"=TeamsActivityAction::class,
*                    "normalization_context"={   "groups"={"get-Teams-With-Projects"}  }
*                 }
*            }
* )
* @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
* @UniqueEntity("teamName", errorPath="teamName", groups={"create-Team"})
*/
class Team implements CreatorEntityInterface
{
    use TimestampableEntity;//this to generate created_At and updated_At

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-Teams-With-Projects"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, unique=true)
     * @Assert\NotBlank()
     * @Groups({"create-Team","get-Teams-With-Projects","get-User","get-Teams-Created-By-User","get-Users-Of-Team"})
     */
    private $teamName;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get-Teams-With-Projects"})
     */
    private $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="createdTeams")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-Users-Of-Team"})
     */
    private $created_by;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="Teams")
     * @Groups({"get-Teams-With-Projects"})
     */
    private $project;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="teams")
     */
    private $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return User
     * @Groups({"get-Users-Of-Team"})
     */
    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    /**
     * @param UserInterface $user
     */
    public function setCreatedBy(UserInterface $created_by): CreatorEntityInterface
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * @Groups({"get-Teams-With-Projects"})
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return \DateTime
     * @Groups({"get-Teams-With-Projects","get-Users-Of-Team"})
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     * @Groups({"get-Teams-With-Projects","get-Users-Of-Team"})
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setTeams($this);
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
            // set the owning side to null (unless already changed)
            if ($member->getTeams() === $this) {
                $member->setTeams(null);
            }
        }

        return $this;
    }

}
