<?php

namespace App\Infrastructure\Doctrine\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "habit")]
class HabitEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $label;

    #[ORM\Column(length: 8)]
    private string $period;

    #[ORM\Column]
    private int $targetCount;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    private DateTimeImmutable $updatedAt;

    #[ORM\OneToMany(mappedBy: 'habit', targetEntity: HabitLogEntity::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $habitLogs;

    public function __construct()
    {
        $this->habitLogs = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): HabitEntity
    {
        $this->id = $id;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): HabitEntity
    {
        $this->label = $label;
        return $this;
    }

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function setPeriod(string $period): HabitEntity
    {
        $this->period = $period;
        return $this;
    }

    public function getTargetCount(): int
    {
        return $this->targetCount;
    }

    public function setTargetCount(int $targetCount): HabitEntity
    {
        $this->targetCount = $targetCount;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): HabitEntity
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): HabitEntity
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * @return Collection<int, HabitLogEntity>
     */
    public function getHabitLogs(): Collection
    {
        return $this->habitLogs;
    }

    public function addHabitLog(HabitLogEntity $log): void
    {
        if (!$this->habitLogs->contains($log)) {
            $this->habitLogs->add($log);
            $log->setHabit($this);
        }
    }

    public function removeHabitLog(HabitLogEntity $log): void
    {
        $this->habitLogs->removeElement($log);
    }
}