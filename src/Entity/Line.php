<?php

namespace App\Entity;

use App\Repository\LineRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LineRepository::class)]
class Line
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getLine"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(["getLine"])]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'yes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getLine"])]
    private ?Article $Articles = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getArticles(): ?Article
    {
        return $this->Articles;
    }

    public function setArticles(?Article $Articles): static
    {
        $this->Articles = $Articles;

        return $this;
    }
}
