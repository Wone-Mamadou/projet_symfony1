<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[Assert\Length(
        min:2,  
        max: 50,
    )]
    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[Assert\Length(min:2,  max : 50)]
    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[Assert\Email(message : "l'email tapé n'est pas valide!"  )]
    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[Assert\Length(min:4,  max :50)]
    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\Column(type: 'datetime')]
    private $createdat;

    
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Article::class)]
    private $articles;
    
    #[Assert\EqualTo(propertyPath:"password", message:"mot de passe incorrect")]
    private $passwordConfirm;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commentaire::class)]
    private $relation;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->createdat = new \Datetime();
        $this->relation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function __toString()
    {
        return $this->firstname.' '.$this->lastname;
    }
    
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): self
    {
        $this->createdat = $createdat;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

    
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(Commentaire $relation): self
    {
        if (!$this->relation->contains($relation)) {
            $this->relation[] = $relation;
            $relation->setUser($this);
        }

        return $this;
    }

    public function removeRelation(Commentaire $relation): self
    {
        if ($this->relation->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getUser() === $this) {
                $relation->setUser(null);
            }
        }

        return $this;
    }
    
    // public function getSalt(){}
    
    public function getSalt(): ?string {
        return null;
    }
    public function eraseCredentials()
    {
        
    }
    // public function getUserIdentifier(): ?string
    // {
        
    // }
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    

}