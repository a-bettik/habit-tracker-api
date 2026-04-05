<?php

namespace App\Application\Habit;

use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitRepository;
use App\Domain\Habit\Period;

class CreateHabit
{
    public function __construct(
        private HabitRepository $habitRepository,
    ) {}

    public function execute(string $label, Period $period = Period::Daily, int $targetCount = 1): Habit
    {
        $habit = Habit::create($label, $period, $targetCount);
        $this->habitRepository->save($habit);

        return $habit;
    }
}