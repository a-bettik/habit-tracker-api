<?php

namespace App\Application\Habit;

use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitRepository;
use App\Domain\Habit\Period;
use Symfony\Component\Uid\Uuid;

class UpdateHabit
{
    public function __construct(
        private HabitRepository $habitRepository,
    ) {}

    public function execute(Uuid $id, string $label, Period $period = Period::Daily, int $targetCount = 1): Habit
    {
        $habit = $this->habitRepository->get($id);
        if ($habit === null) {
            throw new \RuntimeException("Habit not found: {$id}");
        }

        $habit->update($label, $period, $targetCount);
        $this->habitRepository->save($habit);

        return $habit;
    }
}