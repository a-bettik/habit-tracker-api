<?php

namespace App\Interface\Habit\Provider;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Domain\Habit\HabitRepository;
use App\Interface\Habit\DTO\HabitOutput;
use Symfony\Component\Uid\Uuid;

class HabitProvider implements ProviderInterface
{
    public function __construct(
        private HabitRepository $habitRepository,
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): HabitOutput|null|array
    {
        // If I got an id, load the Habit
        if (isset($uriVariables['id'])) {
            $habit = $this->habitRepository->get(Uuid::fromString($uriVariables['id']));
            return $habit ? HabitOutput::fromDomain($habit) : null;
        }

        // Else if GetCollection, get all
        if ($operation instanceof GetCollection) {
            return array_map(
                fn($habit) => HabitOutput::fromDomain($habit),
                $this->habitRepository->findAll(),
            );
        }

        return null;
    }
}
