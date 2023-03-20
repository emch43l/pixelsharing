<?php

namespace App\Entity;

use App\Repository\UserStatisticsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserStatisticsRepository::class)]
class UserStatistics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $totalShares = null;

    #[ORM\Column]
    private ?int $totalActions = null;

    #[ORM\OneToOne(inversedBy: 'userStatistics', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalShares(): ?int
    {
        return $this->totalShares;
    }

    public function setTotalShares(int $totalShares): self
    {
        $this->totalShares = $totalShares;

        return $this;
    }

    public function getTotalActions(): ?int
    {
        return $this->totalActions;
    }

    public function setTotalActions(int $totalActions): self
    {
        $this->totalActions = $totalActions;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
