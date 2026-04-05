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
use App\Interface\Habit\Provider\HabitProvider;
use App\State\CompleteHabitProcessor;
use App\State\HabitProcessor;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Habit',
    operations: [
        new GetCollection(provider: HabitProvider::class),
        new Get(provider: HabitProvider::class),
        new Post(
            provider: HabitProvider::class,
            processor: HabitProcessor::class,
        ),
        new Put(provider: HabitProvider::class),
        new Delete(provider: HabitProvider::class),
        new Post(
            uriTemplate: '/habits/{id}/complete',
            input: false,
            name: 'complete_habit',
            provider: HabitProvider::class,
            processor: CompleteHabitProcessor::class,
        )
    ],
    input: HabitInput::class,
    output: HabitOutput::class
)]
class HabitResource {
    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;
}