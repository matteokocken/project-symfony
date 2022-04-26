<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[  ORM\Table(name:"im22_users"),
    ORM\Entity(repositoryClass: UserRepository::class),
    UniqueEntity(
        fields: ['firstName', 'lastName'],
        message: 'Ce couple nom/prenom n\'est pas unique',
    ),
]

class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[  ORM\Column(type: 'string', length: 100),
        Assert\NotBlank(message: 'Votre login ne doit pas être vide !')
    ]
    private $login;

    #[ORM\Column(type: 'string', length: 100),
        Assert\NotBlank(message: 'Votre prénom ne doit pas être vide !')
    ]
    private $firstName;

    #[ORM\Column(type: 'string', length: 100),
        Assert\NotBlank(message: 'Votre nom ne doit pas être vide !')
    ]
    private $lastName;

    #[ORM\Column(type: 'datetime')]
    private $dateOfBirth;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Panier::class)]
    private $paniers;


    public function __construct()
    {
        $this->Panier = new ArrayCollection();
        $this->paniers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
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

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->setUser($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getUser() === $this) {
                $panier->setUser(null);
            }
        }

        return $this;
    }
}
