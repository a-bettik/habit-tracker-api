<?php

namespace App\Interface\Habit\Processor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Habit\CreateHabit;
use App\Application\Habit\DeleteHabit;
use App\Application\Habit\UpdateHabit;
use App\Domain\Habit\Period;
use App\Interface\Habit\DTO\HabitInput;
use App\Interface\Habit\DTO\HabitOutput;
use Symfony\Component\Uid\Uuid;

final class HabitProcessor implements ProcessorInterface
{
    public function __construct(
        private CreateHabit $createHabit,
        private UpdateHabit $updateHabit,
        private DeleteHabit $deleteHabit,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var HabitInput $data */
        if ($operation instanceof Post) {
            // Creation
            $habit = $this->createHabit->execute(
                $data->label,
                Period::from($data->period),
                $data->targetCount
            );
        } elseif ($operation instanceof Patch || $operation instanceof Put) {
            // Update
            $habit = $this->updateHabit->execute(
                Uuid::fromString($uriVariables['id']),
                $data->label,
                Period::from($data->period),
                $data->targetCount
            );
        } elseif ($operation instanceof Delete) {
            /** @var HabitOutput $data */
            $this->deleteHabit->execute($data->id);
            return null;
        } else {
            throw new \RuntimeException("Unsupported operation: {$operation->getName()}");
        }

        return HabitOutput::fromDomain($habit);
    }
}
