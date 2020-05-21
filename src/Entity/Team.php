<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use App\Entity\Interfaces\CreatorEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

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
*                   "access_control"="is_granted('ROLE_CHEF_PROJET')"
*                },
*               "get"
*           }
* )
* @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
*/
class Team implements CreatorEntityInterface
{
    use TimestampableEntity;//this to generate created_At and updated_At

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer").
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     * @Groups({"get-User","get-Teams-Created-By-User","get-Users-Of-Team"})
     */
    private $teamName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="teams")
     * @Groups({"get-Teams-Created-By-User","get-Users-Of-Team"})
     * @ApiSubresource()
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="createdTeams")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("get-Users-Of-Team")
     */
    private $created_by;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="Teams")
     */
    private $project;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addTeam($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeTeam($this);
        }

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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

}
