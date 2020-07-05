<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Interfaces\CreatorEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;


/**
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *         "id": "exact",
 *         "projectName": "partial",
 *         "created_by.username": "partial"
 *     }
 * )
 * @ApiFilter(
 *     DateFilter::class,
 *     properties={
 *         "createdAt"
 *     }
 * )
 * @ApiFilter(RangeFilter::class, properties={"id"})
 * @ApiFilter(
 *     OrderFilter::class,
 *     properties={
 *         "id",
 *         "projectName",
 *         "createdAt"
 *     },
 *     arguments={"orderParameterName"="_order"}
 * )
 * @ApiFilter(PropertyFilter::class, arguments={
 *     "parameterName": "properties",
 *     "overrideDefaultProperties": false,
 *     "whitelist": {"id", "projectName"}
 * })
 * @ApiResource(
 *     attributes={
 *         "order"={"createdAt": "DESC"}, "maximum_items_per_page"=3
 *     },
 *     collectionOperations={
 *       "post"={
 *           "security"="is_granted('ROLE_CHEF_PROJET')", "security_message"="Only CHEF can add projects.",  
 *           "denormalization_context"={ "groups"={"create-Project"} },
 *           "normalization_context"={  "groups"={"get-Project"}  } 
 *        },
 *        "get"={
 *           "security"="is_granted('ROLE_DEV')", "security_message"="Sorry, but you should be a developper.",
 *           "normalization_context"={  "groups"={"get-Project"}  }
 *        }
 *     },
 *    itemOperations={
 *        "get"={
 *           "security"="is_granted('ROLE_DEV')", "security_message"="Sorry, but you should be a developper.",
 *           "normalization_context"={  "groups"={"get-Project"}  }
 *        },
 *        "patch"={
 *            "access_control"="is_granted('ROLE_CHEF_PROJET')",
 *            "input_formats"={"json"={"application/json"}},
 *            "method"="PATCH",
 *            "normalization_context"={   "groups"={"get-Project"}  }
 *        }
 *    }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @UniqueEntity("projectName", errorPath="projectName", groups={"create-Project"})
 */
class Project implements CreatorEntityInterface
{
    use TimestampableEntity;//this to generate created_At and updated_At

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-Project","get-Teams-With-Projects","get-Owner","get-Task-with-comments","get-Task-with-Bugs"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Groups({"create-Project","get-Project","get-Teams-With-Projects","get-Task-with-comments","get-Task-with-Bugs"})
     */
    private $projectName;

    /**
     * @Groups({"create-Project","get-Project","get-Task-with-comments"})
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="project")
     */
    private $Teams;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get-Teams-With-Projects","get-Project"})
     */
    private $enabled;

    /**
     * @Groups({"get-Project"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="IdProject")
     * @ApiSubresource()
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bug", mappedBy="IdProject")
     * @ApiSubresource()
     */
    private $bugs;

    public function __construct()
    {
        $this->Teams = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->bugs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectName(): ?string
    {
        return $this->projectName;
    }

    public function setProjectName(string $projectName): self
    {
        $this->projectName = $projectName;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->Teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->Teams->contains($team)) {
            $this->Teams[] = $team;
            $team->setProject($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->Teams->contains($team)) {
            $this->Teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getProject() === $this) {
                $team->setProject(null);
            }
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
     * @Groups({"get-Project"})
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
     * @return \DateTime
     * @Groups({"get-Project"})
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     * @Groups({"get-Project"})
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setIdProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getIdProject() === $this) {
                $task->setIdProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Bug[]
     */
    public function getBugs(): Collection
    {
        return $this->bugs;
    }

    public function addBug(Bug $bug): self
    {
        if (!$this->bugs->contains($bug)) {
            $this->bugs[] = $bug;
            $bug->setIdProject($this);
        }

        return $this;
    }

    public function removeBug(Bug $bug): self
    {
        if ($this->bugs->contains($bug)) {
            $this->bugs->removeElement($bug);
            // set the owning side to null (unless already changed)
            if ($bug->getIdProject() === $this) {
                $bug->setIdProject(null);
            }
        }

        return $this;
    }
}
