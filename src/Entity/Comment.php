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
 *           attributes={
 *              "order"={"createdAt": "DESC"}
 *           },
 *           collectionOperations={
 *               "post"={
 *                   "security"="is_granted('ROLE_DEV')", "security_message"="Only Developper can add projects.",
 *                   "denormalization_context"={ "groups"={"create-Comment"} },
 *                   "normalization_context"={  "groups"={"get-Comment"}  }
 *                },
 *               "get"={
 *                   "security"="is_granted('ROLE_DEV')", "security_message"="Sorry, but you should be a developper.",
 *                   "normalization_context"={  "groups"={"get-Comment"}  }
 *               }
 *           },
 *           itemOperations={
 *              "get"={
 *                  "security"="is_granted('ROLE_DEV')", "security_message"="Sorry, but you should be a developper.",
 *                  "normalization_context"={  "groups"={"get-Comment"}  }
 *              }
 *           }
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
     */
    private $id;

    /**
     * @Groups({"get-Comment","create-Comment"})
     * @ORM\Column(type="text")
     * @Assert\NotBlank(groups={"create-Comment"})
     */
    private $content;

    /**
     * @Groups({"get-Comment"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $created_by;

    /**
     * @Groups({"get-Comment","create-Comment"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Task", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Task;

    /**
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
     * @Groups({"get-Comment"})
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
