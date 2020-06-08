<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Interfaces\CreatorEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ApiResource(
 *      attributes={
 *         "order"={"createdAt": "DESC"}, "maximum_items_per_page"=3
 *      },
 *      collectionOperations={
 *         "post"={
 *            "security"="is_granted('ROLE_CHEF_PROJET')", "security_message"="Only CHEF can add Labels.",
 *            "denormalization_context"={ "groups"={"create-Label"} }
 *          },
 *          "get"
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\LabelsRepository")
 * @UniqueEntity("labelName", errorPath="labelName", groups={"create-Label"})
 */
class Labels implements CreatorEntityInterface
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, unique=true)
     * @Groups({"create-Label","get-Task-with-comments"})
     * @Assert\NotBlank(groups={"create-Label"})
     */
    private $labelName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Task", mappedBy="labels")
     */
    private $tasks;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @Groups({"create-Label","get-Task-with-comments"})
     * @ORM\Column(type="string", length=25)
     */
    private $color;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabelName(): ?string
    {
        return $this->labelName;
    }

    public function setLabelName(string $labelName): self
    {
        $this->labelName = $labelName;

        return $this;
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
            $task->addLabel($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            $task->removeLabel($this);
        }

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
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

}
