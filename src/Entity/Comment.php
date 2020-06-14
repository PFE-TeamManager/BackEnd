<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Interfaces\CreatorEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={
 *         "normalization_context"={"groups"={"get-Task-with-comments"}},
 *         "order"={"createdAt": "DESC"}, "maximum_items_per_page"=3,
 *         "pagination_client_enabled"=true,
 *         "pagination_client_items_per_page"=true
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={
 *             "security"="is_granted('ROLE_DEV') and object.getCreatedBy() == user)",
 *             "security_message"="Sorry, but you should be a developper.",
 *         }
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"={
 *             "security"="is_granted('ROLE_DEV')", 
 *             "security_message"="Sorry, but you should be a developper.",
 *             "normalization_context"={
 *                 "groups"={"get-Task-with-comments"}
 *             }
 *         },
 *         "api_tasks_comments_get_subresource"={
 *             "normalization_context"={
 *                 "groups"={"get-Comment"}
 *             }
 *         }
 *     },
 *     denormalizationContext={
 *         "groups"={"create-Comment"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment implements CreatorEntityInterface
{
    use TimestampableEntity;//this to generate created_At and updated_At

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-Task-with-comments","get-Comment"})
     */
    private $id;

    /**
     * @Groups({"get-Task-with-comments","create-Comment","get-Comment"})
     * @ORM\Column(type="text")
     * @Assert\NotBlank(groups={"create-Comment"})
     */
    private $content;

    /**
     * @Groups({"get-Task-with-comments"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @Groups({"create-Comment"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Task", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Task;

    /**
     * @Groups({"get-Task-with-comments"})
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getTask(): ?Task
    {
        return $this->Task;
    }

    public function setTask(?Task $Task): self
    {
        $this->Task = $Task;

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
}
