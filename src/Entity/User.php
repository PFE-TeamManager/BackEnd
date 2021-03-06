<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\ResetPasswordAction;
use App\Controller\UsersDatableAction;

/**
 * Groups is a way of identifying a set of properties that should be serialized
 * Group get-Owner is for the profile of the user
 * put-reset-password is a custom operation, that signify nothing to APIPlatform 
 * so we must provide the method and path and controller
 * @ApiResource(
 *    subresourceOperations={
 *       "api_users_created_teams_get_subresource" = {
 *           "method"="GET",
 *           "normalization_context"={  "groups"={"get-Teams-Created-By-User"}  }
 *         },
 *       "api_teams_users_get_subresource" = {
 *           "method"="GET",
 *           "normalization_context"={  "groups"={"get-Users-Of-Team"}  }                        
 *        }
 *     },
 *     collectionOperations={
 *          "post"={  
 *              "denormalization_context"={ "groups"={"create-User"} },
 *              "normalization_context"={  "groups"={"get-User"}  },
 *              "validation_groups"={"create-User"}
 *           },
 *          "get"={
 *             "access_control"="is_granted('ROLE_DEV')",
 *             "method"="GET",
 *             "path"="/usersdatatable",
 *             "controller"=UsersDatableAction::class,
 *             "normalization_context"={
 *                 "groups"={"get-Users-datatable"}
 *             }
 *          }
 *      },
 *     itemOperations={
 *          "patch"={
 *             "access_control"="is_granted('ROLE_MEMBRE')",
 *             "input_formats"={"json"={"application/json"}},
 *             "method"="PATCH",
 *             "denormalization_context"={
 *                 "groups"={"patch-user"}
 *             },
 *             "normalization_context"={   "groups"={"get-User"}  }
 *          },
 *         "put-reset-password"={
 *             "access_control"="is_granted('ROLE_MEMBRE') and object == user",
 *             "method"="PUT",
 *             "path"="/users/{id}/reset-password",
 *             "controller"=ResetPasswordAction::class,
 *             "denormalization_context"={
 *                 "groups"={"put-reset-password"}
 *             },
 *             "validation_groups"={"put-reset-password"}
 *          },
 *          "get"={
 *             "access_control"="is_granted('ROLE_MEMBRE') and object == user",
 *             "normalization_context"={
 *                 "groups"={"get-Project"}
 *             }
 *          },
 *          "put"={
 *             "access_control"="is_granted('ROLE_MEMBRE') and object == user",
 *             "denormalization_context"={
 *                 "groups"={"put-user"}
 *             },
 *             "normalization_context"={
 *                 "groups"={"get-User"}
 *             }
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username", errorPath="username", groups={"create-User"})
 * @UniqueEntity("email", groups={"create-User"})
 */
class User implements UserInterface
{
    use TimestampableEntity;//this to generate created_At and updated_At

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-Users-datatable","get-Owner","get-Teams-Created-By-User","get-Users-Of-Team","get-Project","get-Task-with-comments","get-Task-with-Bugs"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(groups={"create-User"})
     * @Assert\Length(min=6, max=255, groups={"create-User"})
     * @Groups({"put-user","create-User","get-Users-datatable","get-Owner","get-Teams-Created-By-User","get-Users-Of-Team","get-Project","get-Task-with-comments","get-Task-with-Bugs"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(groups={"create-User"})
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @Groups({"put-user","get-Users-datatable","get-Owner","create-User","get-User","get-Team-With-Members"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"create-User"})
     * @Assert\NotBlank( groups={"create-User"} )
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Password must be seven characters long and contain at least one digit, one upper case letter and one lower case letter"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=14, nullable=true, unique=true)
     * @Assert\Regex(
     *     pattern="/^\(0\)[0-9]*$",
     *     message="Phone number should contain 9 digits"
     * )
     * @Groups({"put-user","create-User","get-Team-With-Members"})
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date( groups={"create-User"} )
     * @Groups({"get-Users-datatable","get-Owner","create-User"})
     */
    private $date_embauchement;



    /**
     * @Groups({"put-reset-password"})
     * @Assert\NotBlank(groups={"put-reset-password"})
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="New Password must be seven characters long and contain at least one digit, one upper case letter and one lower case letter"
     * )
     */
    private $newPassword;

    /**
     * @Groups({"put-reset-password"})
     * @Assert\NotBlank(groups={"put-reset-password"})
     * @Assert\Expression(
     *     "this.getNewPassword() === this.getNewRetypedPassword()",
     *     message="New Passwords does not match"
     * )
     */
    private $newRetypedPassword;

    /**
     * @Groups({"put-reset-password"})
     * @Assert\NotBlank(groups={"put-reset-password"})
     * @UserPassword(groups={"put-reset-password"})
     */
    private $oldPassword;





    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $passwordChangeDate;

    /**
     * @ORM\Column(type="json")
     * @Groups({"get-Users-datatable","get-Owner","patch-user","get-User"})
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     * @Groups({"get-Owner"})
     */
    private $date_resignation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="created_by", orphanRemoval=true)
     * @ApiSubresource()
     */
    private $createdTeams;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get-Users-datatable","get-Owner"})
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     * @Groups({"get-User"})
     */
    private $confirmationToken;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="created_by", orphanRemoval=true)
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="created_by", orphanRemoval=true)
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="created_by", orphanRemoval=true)
     */
    private $comments;

    /**
     * @Groups({"patch-user","get-User","get-Owner"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="members")
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="user")
     * @ApiSubresource()
     */
    private $affectedTasks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bug", mappedBy="created_by", orphanRemoval=true)
     */
    private $bugs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bug", mappedBy="user")
     * @ApiSubresource()
     */
    private $affectedBugs;

    /**
     * @Groups({"patch-user","get-Owner"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->createdTeams = new ArrayCollection();
        $this->enabled = false;
        $this->confirmationToken = null;
        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->affectedTasks = new ArrayCollection();
        $this->bugs = new ArrayCollection();
        $this->affectedBugs = new ArrayCollection();
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        //$roles[] = 'ROLE_MEMBRE';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getDateEmbauchement()
    {
        return $this->date_embauchement;
    }

    public function setDateEmbauchement($date_embauchement): void
    {
        $this->date_embauchement = $date_embauchement;
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


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Team[]
     */
    public function getCreatedTeams(): Collection
    {
        return $this->createdTeams;
    }

    public function addCreatedTeam(Team $createdTeam): self
    {
        if (!$this->createdTeams->contains($createdTeam)) {
            $this->createdTeams[] = $createdTeam;
            $createdTeam->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedTeam(Team $createdTeam): self
    {
        if ($this->createdTeams->contains($createdTeam)) {
            $this->createdTeams->removeElement($createdTeam);
            // set the owning side to null (unless already changed)
            if ($createdTeam->getCreatedBy() === $this) {
                $createdTeam->setCreatedBy(null);
            }
        }

        return $this;
    }


    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword): void
    {
        $this->newPassword = $newPassword;
    }

    public function getNewRetypedPassword(): ?string
    {
        return $this->newRetypedPassword;
    }

    public function setNewRetypedPassword($newRetypedPassword): void
    {
        $this->newRetypedPassword = $newRetypedPassword;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    public function getPasswordChangeDate()
    {
        return $this->passwordChangeDate;
    }

    public function setPasswordChangeDate($passwordChangeDate): void
    {
        $this->passwordChangeDate = $passwordChangeDate;
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

    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken($confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }


    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setCreatedBy($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getCreatedBy() === $this) {
                $project->setCreatedBy(null);
            }
        }

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
            $task->setCreatedBy($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getCreatedBy() === $this) {
                $task->setCreatedBy(null);
            }
        }

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
            $comment->setCreatedBy($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCreatedBy() === $this) {
                $comment->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @Groups({"get-User"})
     */
    public function getTeams(): ?Team
    {
        return $this->teams;
    }

    public function setTeams(?Team $teams): self
    {
        $this->teams = $teams;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getAffectedTasks(): Collection
    {
        return $this->affectedTasks;
    }

    public function addAffectedTask(Task $affectedTask): self
    {
        if (!$this->affectedTasks->contains($affectedTask)) {
            $this->affectedTasks[] = $affectedTask;
            $affectedTask->setUser($this);
        }

        return $this;
    }

    public function removeAffectedTask(Task $affectedTask): self
    {
        if ($this->affectedTasks->contains($affectedTask)) {
            $this->affectedTasks->removeElement($affectedTask);
            // set the owning side to null (unless already changed)
            if ($affectedTask->getUser() === $this) {
                $affectedTask->setUser(null);
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
            $bug->setCreatedBy($this);
        }

        return $this;
    }

    public function removeBug(Bug $bug): self
    {
        if ($this->bugs->contains($bug)) {
            $this->bugs->removeElement($bug);
            // set the owning side to null (unless already changed)
            if ($bug->getCreatedBy() === $this) {
                $bug->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Bug[]
     */
    public function getAffectedBugs(): Collection
    {
        return $this->affectedBugs;
    }

    public function addAffectedBug(Bug $affectedBug): self
    {
        if (!$this->affectedBugs->contains($affectedBug)) {
            $this->affectedBugs[] = $affectedBug;
            $affectedBug->setUser($this);
        }

        return $this;
    }

    public function removeAffectedBug(Bug $affectedBug): self
    {
        if ($this->affectedBugs->contains($affectedBug)) {
            $this->affectedBugs->removeElement($affectedBug);
            // set the owning side to null (unless already changed)
            if ($affectedBug->getUser() === $this) {
                $affectedBug->setUser(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
