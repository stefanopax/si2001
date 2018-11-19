<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Skill", cascade={"persist"})
     * @ORM\JoinTable(name="has",
     *      joinColumns={@ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="skill", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     */
    private $skill_ids;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", cascade={"persist"})
     * @ORM\JoinTable(name="owns",
     *      joinColumns={@ORM\JoinColumn(name="user", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role", referencedColumnName="id")}
     *      )
     */
    private $role_ids;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", cascade={"persist"})
     * @ORM\JoinColumn(name="status", referencedColumnName="id", onDelete="SET NULL")
     */
    private $status;

    public function __construct()
    {
        $this->skill_ids = new ArrayCollection();
        $this->role_ids = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = [];
        foreach ($this->role_ids as $r) {
            $roles[] = $r->getName();
        }

        return $roles;
    }

    public function getRoleIds(): Collection
    {
        return $this->role_ids;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized, ['allowed classes' => false]);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Collection|Skill[]
     */
    public function getSkillIds(): Collection
    {
        return $this->skill_ids;
    }

    public function addSkillId(Skill $skillId): self
    {
        if (!$this->skill_ids->contains($skillId)) {
            $this->skill_ids->add($skillId);
        }

        return $this;
    }

    public function removeSkillId(Skill $skillId): self
    {
        if ($this->skill_ids->contains($skillId)) {
            $this->skill_ids->removeElement($skillId);
        }

        return $this;
    }

    public function addRole(Role $role): self
    {
        if (!$this->role_ids->contains($role)) {
            $this->role_ids->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->role_ids->contains($role)) {
            $this->role_ids->removeElement($role);
        }

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }
}
