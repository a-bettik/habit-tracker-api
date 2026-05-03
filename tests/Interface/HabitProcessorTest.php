<?php

namespace App\Tests\Interface;

use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Application\Habit\CreateHabit;
use App\Application\Habit\DeleteHabit;
use App\Application\Habit\UpdateHabit;
use App\Domain\Habit\Habit;
use App\Domain\Habit\Period;
use App\Interface\Habit\DTO\HabitInput;
use App\Interface\Habit\DTO\HabitOutput;
use App\Interface\Habit\Processor\HabitProcessor;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HabitProcessorTest extends TestCase
{
    #[Test]
    public function it_creates_a_habit()
    {
        $createHabit = $this->createMock(CreateHabit::class);
        $updateHabit = $this->createMock(UpdateHabit::class);
        $deleteHabit = $this->createMock(DeleteHabit::class);

        $habitProcessor = new HabitProcessor($createHabit, $updateHabit, $deleteHabit);
        $habitInput = new HabitInput();

        // DTO
        $habitInput->label = 'A new habit';
        $habitInput->targetCount = 1;
        $habitInput->period = Period::Weekly->value;

        // Expected Domain Object
        $expectedHabit = Habit::create('A new habit', Period::Weekly, 1);

        $createHabit
            ->expects($this->once())
            ->method('execute')
            ->with('A new habit', Period::Weekly, 1)
            ->willReturn($expectedHabit);

        // No other use case will be called
        $updateHabit
            ->expects($this->never())
            ->method('execute');
        $deleteHabit
            ->expects($this->never())
            ->method('execute');

        // Act
        $result = $habitProcessor->process($habitInput, new Post());

        $this->assertInstanceOf(HabitOutput::class, $result);
    }

    #[Test]
    public function it_updates_habit()
    {
        $createHabit = $this->createMock(CreateHabit::class);
        $updateHabit = $this->createMock(UpdateHabit::class);
        $deleteHabit = $this->createMock(DeleteHabit::class);

        $habitProcessor = new HabitProcessor($createHabit, $updateHabit, $deleteHabit);
        $habitInput = new HabitInput();

        // Initial Habit
        $createdHabit = Habit::create('A new habit', Period::Weekly, 1);
        // Expected after update
        $expectedHabit = Habit::create('An updated habit', Period::Weekly, 2);

        // DTO for update
        $habitInput->label = 'An updated habit';
        $habitInput->targetCount = 2;
        $habitInput->period = Period::Weekly->value;

        // Update the Habit
        $uriVariables = [
            'id' => $createdHabit->getId(),
        ];

        $updateHabit
            ->expects($this->once())
            ->method('execute')
            ->with($createdHabit->getId(), 'An updated habit', Period::Weekly, 2)
            ->willReturn($expectedHabit);

        // No other use case will be called
        $createHabit
            ->expects($this->never())
            ->method('execute');
        $deleteHabit
            ->expects($this->never())
            ->method('execute');

        // Act
        $result = $habitProcessor->process($habitInput, new Put(), $uriVariables);

        $this->assertInstanceOf(HabitOutput::class, $result);
    }
}