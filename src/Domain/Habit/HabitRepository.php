<?php

namespace App\Domain\Habit;

use Symfony\Component\Uid\Uuid;

interface HabitRepository
{
    public function save(Habit $habit): void;

    public function get(Uuid $id): ?Habit;

    /** @return Habit[] */
    public function findAll(): array;
}