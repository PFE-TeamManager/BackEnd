<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
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
 *         "order"={"createdAt": "DESC"}
 *     },
 *     collectionOperations={
 *       "post"={  
 *           "denormalization_context"={ "groups"={"create-Project"} },
 *           "normalization_context"={  "groups"={"get-Project"}  } 
 *        },
 *        "get"
 *     },
 *    itemOperations={
 *        "get"={
 *           "normalization_context"={  "groups"={"get-Project"}  }
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
     * @Groups({"get-Project"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"create-Project","get-Project"})
     */
    private $projectName;

    /**
     * @Groups({"get-Project"})
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="project")
     */
    private $Teams;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @Groups({"get-Project"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    public function __construct()
    {
        $this->Teams = new ArrayCollection();
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
}
