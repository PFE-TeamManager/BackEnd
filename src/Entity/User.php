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

/**
 * Groups is a way of identifying a set of properties that should be serialized
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
 *     collectionOperations={
 *          "post"={
 *             "denormalization_context"={
 *                 "groups"={"post"}
 *             },
 *             "normalization_context"={
 *                 "groups"={"get"}
 *             }
 *          }
 *      },
 *     itemOperations={
 *          "get"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY')",
 *             "normalization_context"={
 *                 "groups"={"get-User"}
 *             }
 *          },
 *          "put"={
 *             "access_control"="is_granted('IS_AUTHENTICATED_FULLY') and object == user",
 *             "denormalization_context"={
 *                 "groups"={"put"}
 *             },
 *             "normalization_context"={
 *                 "groups"={"get"}
 *             }
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username", errorPath="username", groups={"post"})
 * @UniqueEntity("email", groups={"post"})
 */
class User implements UserInterface
{
    use TimestampableEntity;//this to generate created_At and updated_At

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-User","get-Teams-Created-By-User","get-Users-Of-Team"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Length(min=6, max=255, groups={"post"})
     * @Groups({"get-User","get-Teams-Created-By-User","get-Users-Of-Team"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *     message="Password must be seven characters long and contain at least one digit, one upper case letter and one lower case letter",
     *     groups={"post"}
     * )
     */
    private $password;

    /**
     * @Groups({"post"})
     * @Assert\NotBlank(groups={"post"})
     * @Assert\Expression(
     *     "this.getPassword() === this.getRetypedPassword()",
     *     message="Passwords does not match",
     *     groups={"post"}
     * )
     */
    private $retypedPassword;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @Groups({"get-User","get-Team-With-Members"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=14, nullable=true, unique=true)
     * @Assert\Regex(
     *     pattern="/^\(0\)[0-9]*$",
     *     message="Phone number should contain 9 digits"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="json")
     * @Groups("get-User")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     * @Groups("get-User")
     */
    private $date_embauchement;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private $date_resignation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Team", inversedBy="users")
     * @Groups("get-User")
     */
    private $teams;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="created_by", orphanRemoval=true)
     * @ApiSubresource()
     */
    private $createdTeams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->createdTeams = new ArrayCollection();
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

    public function getRetypedPassword()
    {
        return $this->retypedPassword;
    }

    public function setRetypedPassword($retypedPassword): void
    {
        $this->retypedPassword = $retypedPassword;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_MEMBRE';

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
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
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

}
