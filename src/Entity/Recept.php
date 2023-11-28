<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReceptRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReceptRepository::class)]
class Recept
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['group1'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    /*     #[Assert\Image] */
    #[Groups(['group1'])]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['group1'])]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'Difficulty must be between 1 and 5',
    )]
    #[Groups(['group1'])]
    private ?int $difficulty = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['group1'])]
    private ?int $time = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['group1'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'no')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Autor $autor = null;

    #[ORM\ManyToOne(inversedBy: 'kategorie')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Kategorie $kategorie = null;

    /*
    *@var App\Entity\Ingredience
    */
    #[ORM\ManyToMany(targetEntity: Ingredience::class, inversedBy: 'recepts')]
    private Collection $ingredience;

    #[ORM\ManyToMany(targetEntity: Nastroj::class, inversedBy: 'recepts')]
    private Collection $nastroje;

    public function __construct()
    {
        $this->ingredience = new ArrayCollection();
        $this->nastroje = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(?int $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(?int $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAutor(): ?Autor
    {
        return $this->autor;
    }

    public function setAutor(?Autor $autor): static
    {
        $this->autor = $autor;

        return $this;
    }

    public function getKategorie(): ?Kategorie
    {
        return $this->kategorie;
    }

    public function setKategorie(?Kategorie $kategorie): static
    {
        $this->kategorie = $kategorie;

        return $this;
    }

    /**
     * @return Collection<int, Ingredience>
     */
    public function getIngredience(): Collection
    {
        return $this->ingredience;
    }

    public function addIngredience(Ingredience $ingredience): static
    {
        if (!$this->ingredience->contains($ingredience)) {
            $this->ingredience->add($ingredience);
        }

        return $this;
    }

    public function removeIngredience(Ingredience $ingredience): static
    {
        $this->ingredience->removeElement($ingredience);

        return $this;
    }

    /**
     * @return Collection<int, Nastroj>
     */
    public function getNastroje(): Collection
    {
        return $this->nastroje;
    }

    public function addNastroje(Nastroj $nastroje): static
    {
        if (!$this->nastroje->contains($nastroje)) {
            $this->nastroje->add($nastroje);
        }

        return $this;
    }

    public function removeNastroje(Nastroj $nastroje): static
    {
        $this->nastroje->removeElement($nastroje);

        return $this;
    }
}
