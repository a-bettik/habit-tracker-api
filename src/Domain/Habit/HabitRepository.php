<?php

namespace App\Domain\Habit;

interface HabitRepository
{
    public function save(Habit $habit): void;

    public function get(int $id): ?Habit;
}