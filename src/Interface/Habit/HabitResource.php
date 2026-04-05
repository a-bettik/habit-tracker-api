<?php

namespace App\Interface\Habit;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Interface\Habit\DTO\HabitInput;
use App\Interface\Habit\DTO\HabitOutput;
use App\Interface\Habit\Processor\CompleteHabitProcessor;
use App\Interface\Habit\Processor\HabitProcessor;
use App\Interface\Habit\Provider\HabitProvider;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Habit',
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            input: HabitInput::class,
            provider: null,
        ),
        new Put(),
        new Delete(
            processor: HabitProcessor::class,
        ),
        new Post(
            uriTemplate: '/habits/{id}/complete',
            input: false,
            name: 'complete_habit',
            processor: CompleteHabitProcessor::class,
        ),
    ],
    input: HabitInput::class,
    output: HabitOutput::class,
    provider: HabitProvider::class,
    processor: HabitProcessor::class,
)]
class HabitResource
{
    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;
}