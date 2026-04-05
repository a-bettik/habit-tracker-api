<?php
namespace App\Domain\Habit;
final class HabitLog
{
    public function __construct(
        private Habit $habit,
        private \DateTimeImmutable $date,
    ) {}

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function isSameDay(\DateTimeImmutable $date): bool
    {
        return $this->date->format('Y-m-d') === $date->format('Y-m-d');
    }
}
