<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\DTO\Habit\HabitInput;
use App\DTO\Habit\HabitOutput;
use App\Repository\HabitRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    input: HabitInput::class,
    output: HabitOutput::class,
    processor: HabitOutput::class,
)]

class Habit
{
    public const PERIOD_DAILY = 'daily';
    public const PERIOD_WEEKLY = 'weekly';
    public const PERIOD_MONTHLY = 'monthly';
    public const PERIOD_YEARLY = 'yearly';

    public const DEFAULT_PERIOD = 'daily';
    public const DEFAULT_TARGET_COUNT = 1;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    private DateTimeImmutable $updatedAt;

    /**
     * @var Collection<int, HabitLog>
     */
    #[ORM\OneToMany(mappedBy: 'habit', targetEntity: HabitLog::class, orphanRemoval: true)]
    private Collection $habitLogs;

    #[ORM\Column(length: 8)]
    private ?string $period = null;

    #[ORM\Column]
    private ?int $targetCount = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->period = self::DEFAULT_PERIOD;
        $this->targetCount = self::DEFAULT_TARGET_COUNT;
        $this->habitLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $now = new DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @return Collection<int, HabitLog>
     */
    public function getHabitLogs(): Collection
    {
        return $this->habitLogs;
    }

    public function addHabitLog(HabitLog $habitLog): static
    {
        if (!$this->habitLogs->contains($habitLog)) {
            $this->habitLogs->add($habitLog);
            $habitLog->setHabit($this);
        }

        return $this;
    }

    public function removeHabitLog(HabitLog $habitLog): static
    {
        if ($this->habitLogs->removeElement($habitLog)) {
            // set the owning side to null (unless already changed)
            if ($habitLog->getHabit() === $this) {
                $habitLog->setHabit(null);
            }
        }

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getTargetCount(): ?int
    {
        return $this->targetCount;
    }

    public function setTargetCount(int $targetCount): static
    {
        $this->targetCount = $targetCount;

        return $this;
    }
}
