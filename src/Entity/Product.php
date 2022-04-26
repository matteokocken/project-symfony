<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[  ORM\Table(name:"im22_product"),
    ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255),
        Assert\NotBlank(message: 'Le nom du produit ne doit pas être vide')
    ]
    private $libelle;

    #[ORM\Column(type: 'integer'),
        Assert\Positive(message: "Le prix doit être positif")
    ]
    private $prix;

    #[ORM\Column(type: 'integer'),
        Assert\PositiveOrZero(message: 'La quantité en stock doit être positive ou nul')
    ]
    private $enStock;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getEnStock(): ?int
    {
        return $this->enStock;
    }

    public function setEnStock(int $enStock): self
    {
        $this->enStock = $enStock;

        return $this;
    }
}
