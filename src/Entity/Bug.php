<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Interfaces\CreatorEntityInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *           attributes={
 *              "normalization_context"={"groups"={"get-Task-with-Bugs"}},
 *              "order"={"createdAt": "DESC"}, "maximum_items_per_page"=6
 *           },
 *           collectionOperations={
 *               "post"={
 *                   "security"="is_granted('ROLE_DEV')", "security_message"="Sorry, but you should be a developper.",
 *                   "denormalization_context"={ "groups"={"create-Bug"} },
 *                   "normalization_context"={  "groups"={"get-Task-with-Bugs"}  }
 *                },
 *               "get"={
 *                   "security"="is_granted('ROLE_DEV')", "security_message"="Sorry, but you should be a developper.",
 *                   "normalization_context"={  "groups"={"get-Task-with-Bugs"}  }
 *               }
 *           },
 *           itemOperations={
 *              "get"={
 *                  "security"="is_granted('ROLE_DEV')", "security_message"="Sorry, but you should be a developper.",
 *                  "normalization_context"={  "groups"={"get-Task-with-Bugs"}  }
 *              },
 *             "patch"={
 *                 "access_control"="is_granted('ROLE_DEV')",
 *                 "input_formats"={"json"={"application/json"}},
 *                 "method"="PATCH",
 *                 "normalization_context"={   "groups"={"get-Task-with-Bugs"}  }
 *              }
 *           }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\BugRepository")
 */
class Bug implements CreatorEntityInterface
{
    use TimestampableEntity;//this to generate created_At and updated_At

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-Task-with-Bugs"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"create-Bug"})
     * @Groups({"get-Task-with-Bugs","create-Bug"})
     */
    private $BugTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"get-Task-with-Bugs","create-Bug"})
     */
    private $BugDescription;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Task", inversedBy="bugs")
     * @Groups({"create-Bug"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdTask;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bugs")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-Task-with-Bugs"})
     */
    private $created_by;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get-Task-with-Bugs"})
     */
    private $enabled;

    /**
     * @Groups({"get-Task-with-Bugs"})
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="Bug")
     * @ApiSubresource()
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBugTitle(): ?string
    {
        return $this->BugTitle;
    }

    public function setBugTitle(string $BugTitle): self
    {
        $this->BugTitle = $BugTitle;

        return $this;
    }

    public function getBugDescription(): ?string
    {
        return $this->BugDescription;
    }

    public function setBugDescription(?string $BugDescription): self
    {
        $this->BugDescription = $BugDescription;

        return $this;
    }

    public function getIdTask(): ?Task
    {
        return $this->IdTask;
    }

    public function setIdTask(?Task $IdTask): self
    {
        $this->IdTask = $IdTask;

        return $this;
    }

    /**
     * @return User
     * @Groups({"get-Task-with-Bugs"})
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
            $comment->setBug($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getBug() === $this) {
                $comment->setBug(null);
            }
        }

        return $this;
    }
}
