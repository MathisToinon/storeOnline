<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getArticle", "getLine"])]
    #[Assert\NotBlank(message: "Le titre du livre est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le titre doit faire au moins {{ limit }} caractères", maxMessage: "Le titre ne peut pas faire plus de {{ limit }} caractères")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getArticle", "getLine"])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getArticle", "getLine"])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(["getArticle", "getLine"])]
    private ?int $price = null;

    #[ORM\Column]
    #[Groups(["getArticle", "getLine"])]
    private ?bool $available = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getArticle", "getLine"])]
    private ?string $link = null;

    #[ORM\OneToMany(mappedBy: 'Articles', targetEntity: Line::class, orphanRemoval: true)]
    private Collection $yes;

    public function __construct()
    {
        $this->yes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return Collection<int, Line>
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(Line $ye): static
    {
        if (!$this->yes->contains($ye)) {
            $this->yes->add($ye);
            $ye->setArticles($this);
        }

        return $this;
    }

    public function removeYe(Line $ye): static
    {
        if ($this->yes->removeElement($ye)) {
            // set the owning side to null (unless already changed)
            if ($ye->getArticles() === $this) {
                $ye->setArticles(null);
            }
        }

        return $this;
    }
}
