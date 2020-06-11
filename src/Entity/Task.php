<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Interfaces\CreatorEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\TasksUserAction;

/**
 * @ApiResource(
 *           attributes={
 *              "order"={"createdAt": "DESC"}, "maximum_items_per_page"=6
 *           },
 *           collectionOperations={
 *               "post"={
 *                   "security"="is_granted('ROLE_CHEF_PROJET')", "security_message"="Only CHEF can add projects.",
 *                   "denormalization_context"={ "groups"={"create-Task"} },
 *                   "normalization_context"={  "groups"={"get-Task-with-comments"}  }
 *                },
 *               "get"={
 *                   "security"="is_granted('ROLE_DEV')", "security_message"="Sorry, but you should be a developper.",
 *                   "normalization_context"={  "groups"={"get-Task-with-comments"}  }
 *               }
 *           },
 *           itemOperations={
 *              "get"={
 *                  "security"="is_granted('ROLE_DEV')", "security_message"="Sorry, but you should be a developper.",
 *                  "normalization_context"={  "groups"={"get-Task-with-comments"}  }
 *              },
 *             "patch"={
 *                 "access_control"="is_granted('ROLE_DEV')",
 *                 "input_formats"={"json"={"application/json"}},
 *                 "method"="PATCH",
 *                 "normalization_context"={   "groups"={"get-Task-with-comments"}  }
 *              }
 *           }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task implements CreatorEntityInterface
{
    use TimestampableEntity;//this to generate created_At and updated_At

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-Task-with-comments"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"create-Task"})
     * @Groups({"get-Task-with-comments","create-Task"})
     */
    private $TaskTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"get-Task-with-comments","create-Task"})
     */
    private $TaskDescription;

    /**
     * @Groups({"get-Task-with-comments","create-Task"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdProject;

    /**
     * @Groups({"get-Task-with-comments"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @Groups({"get-Task-with-comments"})
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @Groups({"get-Task-with-comments"})
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="Task", orphanRemoval=true)
     * @ApiSubresource()
     */
    private $comments;

    /**
     * @Groups({"create-Task","get-Task-with-comments"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Labels", inversedBy="tasks")
     */
    private $labels;

    /**
     * @Groups({"get-Task-with-comments"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="affectedTasks")
     */
    private $user;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->labels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskTitle(): ?string
    {
        return $this->TaskTitle;
    }

    public function setTaskTitle(string $TaskTitle): self
    {
        $this->TaskTitle = $TaskTitle;

        return $this;
    }

    public function getTaskDescription(): ?string
    {
        return $this->TaskDescription;
    }

    public function setTaskDescription(?string $TaskDescription): self
    {
        $this->TaskDescription = $TaskDescription;

        return $this;
    }

    public function getIdProject(): ?Project
    {
        return $this->IdProject;
    }

    public function setIdProject(?Project $IdProject): self
    {
        $this->IdProject = $IdProject;

        return $this;
    }

    /**
     * @return User
     * @Groups({"get-Task-with-comments"})
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
     * @Groups({"get-Task-with-comments"})
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     * @Groups({"get-Task-with-comments"})
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

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTask($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getTask() === $this) {
                $comment->setTask(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Labels[]
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(Labels $label): self
    {
        if (!$this->labels->contains($label)) {
            $this->labels[] = $label;
        }

        return $this;
    }

    public function removeLabel(Labels $label): self
    {
        if ($this->labels->contains($label)) {
            $this->labels->removeElement($label);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
