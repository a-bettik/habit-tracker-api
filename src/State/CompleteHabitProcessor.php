<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Application\Habit\CompleteHabit;
use App\Interface\Habit\DTO\HabitOutput;
use DateTimeImmutable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

final class CompleteHabitProcessor implements ProcessorInterface
{
    public function __construct(
        private CompleteHabit $completeHabit,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        try {
            $habit = $this->completeHabit->execute(Uuid::fromString($uriVariables['id']), new DateTimeImmutable());
        } catch (\RuntimeException $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }

        return HabitOutput::fromDomain($habit);
    }
}