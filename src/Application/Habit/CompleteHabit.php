<?php

namespace App\Application\Habit;

use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitRepository;
use Symfony\Component\Uid\Uuid;

class CompleteHabit
{
    public function __construct(
        private HabitRepository $habitRepository,
    ) {}

    public function execute(Uuid $id, \DateTimeImmutable $date): Habit
    {
        $habit = $this->habitRepository->get($id);
        if ($habit === null) {
            throw new \RuntimeException("Habit not found: {$id}");
        }

        $habit->complete($date);
        $this->habitRepository->save($habit);

        return $habit;
    }
}