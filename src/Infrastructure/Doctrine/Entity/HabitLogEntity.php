<?php

namespace App\Infrastructure\Doctrine\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "habit_log")]
class HabitLogEntity
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?DateTimeImmutable $date = null;

    #[ORM\ManyToOne(targetEntity: HabitEntity::class, inversedBy: 'habitLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HabitEntity $habit = null;

    public function __construct(HabitEntity $habit, DateTimeImmutable $date)
    {
        $this->habit = $habit;
        $this->date = $date;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHabit(): ?HabitEntity
    {
        return $this->habit;
    }

    public function setHabit(?HabitEntity $habit): static
    {
        $this->habit = $habit;

        return $this;
    }
}
