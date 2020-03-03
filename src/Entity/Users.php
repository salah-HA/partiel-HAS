<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity("email")
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prénom ne peut pas être vide")
     * @Assert\Length(min="2", minMessage="Le prénom est trop petit",
     *      max="255", maxMessage="Le prénom est trop long")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom de famille ne peut pas être vide")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email(message="L'email est invalide")
     * @Assert\NotBlank(message="L'email ne peut pas être vide")
     */
    private $email;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Type(type="DateTime")
     */
    private $birthDate;
/*
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user")
     *//*
    private $articles;
*/
    /**
     * @ORM\Column(type="string")
     */
    private $roles;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    public function __construct()
    {
       /* $this->articles = new ArrayCollection();*/
        $this->roles = 'ROLE_USER';
        $this->createAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $BirthDate): self
    {
        $this->birthDate = $BirthDate;

        return $this;
    }
/*
    /**
     * @return Collection|Article[]
     *//*
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }
*/
    public function getRoles()
    {
        return [$this->roles];
    }

    public function setRoles(string $roles): Users
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function getSalt(){
        return null;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }
}
