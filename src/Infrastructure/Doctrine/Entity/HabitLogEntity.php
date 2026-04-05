<?php

namespace App\Infrastructure\Doctrine\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: "habit_log")]
class HabitLogEntity
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column]
    private ?DateTimeImmutable $date = null;

    #[ORM\ManyToOne(targetEntity: HabitEntity::class, inversedBy: 'habitLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HabitEntity $habit = null;

    public function __construct(HabitEntity $habit, DateTimeImmutable $date)
    {
        $this->id = Uuid::v4();
        $this->habit = $habit;
        $this->date = $date;
    }

    public function getId(): Uuid
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
