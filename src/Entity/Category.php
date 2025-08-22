<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\UniqueConstraint(columns: ['name'])]
#[UniqueEntity(fields: ['name'])]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    /**
     * @var Collection<int, Wish>
     */
    #[ORM\OneToMany(targetEntity: Wish::class, mappedBy: 'category', orphanRemoval: true)]
    #[Assert\NotBlank()]
    private Collection $wish;

    public function __construct()
    {
        $this->wish = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Wish>
     */
    public function getWish(): Collection
    {
        return $this->wish;
    }

    public function addWish(Wish $wish): static
    {
        if (!$this->wish->contains($wish)) {
            $this->wish->add($wish);
            $wish->setCategory($this);
        }

        return $this;
    }

    public function removeWish(Wish $wish): static
    {
        if ($this->wish->removeElement($wish)) {
            // set the owning side to null (unless already changed)
            if ($wish->getCategory() === $this) {
                $wish->setCategory(null);
            }
        }

        return $this;
    }
}
