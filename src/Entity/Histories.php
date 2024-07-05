<?php

namespace App\Entity;

use App\Repository\HistoriesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriesRepository::class)]
class Histories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;


    #[ORM\ManyToOne(inversedBy: 'histories')]
    private ?User $users = null;

    #[ORM\ManyToOne(inversedBy: 'histories')]
    private ?Tutorials $tutorials = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $progression = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getTutorials(): ?Tutorials
    {
        return $this->tutorials;
    }

    public function setTutorials(?Tutorials $tutorials): static
    {
        $this->tutorials = $tutorials;

        return $this;
    }

    public function getProgression(): ?array
    {
        return $this->progression;
    }

    public function setProgression(?array $progression): static
    {
        $this->progression = $progression;

        return $this;
    }
}
