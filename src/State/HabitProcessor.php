<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Habit\DTO\HabitInput;
use App\Application\Habit\CreateHabit;
use App\Domain\Habit\Period;

final class HabitProcessor implements ProcessorInterface
{
    public function __construct(
        private CreateHabit $createHabit,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var HabitInput $data */
        return $this->createHabit->execute(
            $data->label,
            Period::from($data->period),
            $data->targetCount
        );
    }
}
