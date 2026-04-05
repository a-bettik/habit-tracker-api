<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Habit\CreateHabit;
use App\Domain\Habit\Period;
use App\Interface\Habit\DTO\HabitInput;
use App\Interface\Habit\DTO\HabitOutput;

final class HabitProcessor implements ProcessorInterface
{
    public function __construct(
        private CreateHabit $createHabit,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var HabitInput $data */
        $habit = $this->createHabit->execute(
            $data->label,
            Period::from($data->period),
            $data->targetCount
        );

        return HabitOutput::fromDomain($habit);
    }
}
