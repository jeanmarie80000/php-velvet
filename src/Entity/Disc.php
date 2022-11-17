<?php

namespace App\Entity;

use App\Repository\DiscRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DiscRepository::class)]
class Disc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @ORM\Column(type="string")
     */
    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\ManyToOne(inversedBy: 'discs')]
    private ?Artist $Artist = null;

    #[ORM\Column(length: 255)]
    private ?string $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    // string est déjà précisé plus haut
    public function setPicture($picture): self
    {
        $this->picture = $picture;
        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->Artist;
    }

    public function setArtist(?Artist $Artist): self
    {
        $this->Artist = $Artist;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }


    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('title', new Assert\Regex([
            'pattern' => '/^[a-zA-Z]+$/',
            'message' => "N'accepte que les lettres",
        ]));

        $metadata->addPropertyConstraint('label', new Assert\Regex([
            'pattern' => '/^[a-zA-Z]+$/',
            'message' => "N'accepte que les lettres",
        ]));

        $metadata->addPropertyConstraint('price', new Assert\Regex([
            'pattern' => '/^[0-9]{1,9}([\.\,]{1}[0-9]{2})?/',
            'message' => "N'accepte que les chiffres avec une virgule ou un point. Ne peut y avoir que deux chiffres après la virgule/le point."
        ]));
    }
}