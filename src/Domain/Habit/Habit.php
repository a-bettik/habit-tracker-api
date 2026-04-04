<?php

namespace App\Domain\Habit;

use DateTimeImmutable;

final class Habit
{
    private function __construct(
        private ?int $id,
        private string $label,
        private Period $period,
        private int $targetCount,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function create(
        string $label,
        Period $period = Period::Daily,
        int $targetCount = 1,
    ): self {
        $now = new DateTimeImmutable();

        return new self(
            null,
            $label,
            $period,
            $targetCount,
            $now,
            $now
        );
    }

    public static function fromPersistence(
        int $id,
        string $label,
        Period $period,
        int $targetCount,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $label,
            $period,
            $targetCount,
            $createdAt,
            $updatedAt
        );
    }

    public function complete(DateTimeImmutable $date): static
    {
        if ($this->isCompletedOn($date)) {
            throw new \DomainException('Habit is already completed for this day.');
        }

        $this->habitLogs []= new HabitLog($this, $date);
        return $this;
    }


    public function isCompletedOn(DateTimeImmutable $date): bool
    {
        foreach ($this->habitLogs as $habitLog) {
            if ($habitLog->isSameDay($date)) {
                return true;
            }
        }
        return false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getPeriod(): Period
    {
        return $this->period;
    }

    public function getTargetCount(): int
    {
        return $this->targetCount;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }


}
