<?php

namespace App\Application\Habit;

use App\Domain\Habit\HabitRepository;
use Symfony\Component\Uid\Uuid;

class DeleteHabit
{
    public function __construct(
        private HabitRepository $habitRepository,
    ) {}

    public function execute(Uuid $id): void
    {
        $habit = $this->habitRepository->get($id);
        if ($habit === null) {
            throw new \RuntimeException("Habit not found: {$id}");
        }

        $this->habitRepository->delete($habit);
    }
}