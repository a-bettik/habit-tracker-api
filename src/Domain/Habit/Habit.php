<?php

namespace App\Domain\Habit;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class Habit
{
    /** @var HabitLog[] */
    private array $habitLogs = [];

    private function __construct(
        private Uuid $id,
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
            Uuid::v4(),
            $label,
            $period,
            $targetCount,
            $now,
            $now,
            []
        );
    }

    public function update(
        string $label = null,
        Period $period = null,
        int $targetCount = null,
    ): self
    {
        if ($label || $period || $targetCount) {
            if ($label) {
                $this->label = $label;
            }
            if ($period) {
                $this->period = $period;
            }
            if ($targetCount) {
                $this->targetCount = $targetCount;
            }
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }

    public static function fromPersistence(
        Uuid $id,
        string $label,
        Period $period,
        int $targetCount,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
        array $habitLogs = [],
    ): self {
        $habit = new self(
            $id,
            $label,
            $period,
            $targetCount,
            $createdAt,
            $updatedAt,
        );
        $habit->habitLogs = [];
        return $habit;
    }

    public function complete(DateTimeImmutable $date): self
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

    public function getId(): Uuid
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

    public function getHabitLogs(): array
    {
        return $this->habitLogs;
    }

}
