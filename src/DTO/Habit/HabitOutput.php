<?php

namespace App\DTO\Habit;
use DateTimeImmutable;

final class HabitOutput
{
    public int $id;
    public string $label;
    public DateTimeImmutable $createdAt;
    public DateTimeImmutable $updatedAt;
}