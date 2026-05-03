<?php

namespace App\Tests\Interface\Habit\Processor;

use ApiPlatform\Metadata\Post;
use App\Application\Habit\CompleteHabit;
use App\Domain\Habit\Habit;
use App\Domain\Habit\Period;
use App\Interface\Habit\DTO\HabitOutput;
use App\Interface\Habit\Processor\CompleteHabitProcessor;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

class CompleteHabitProcessorTest extends TestCase
{
    #[Test]
    public function it_completes_a_habit()
    {
        $completeHabit = $this->createMock(CompleteHabit::class);
        $now = new \DateTimeImmutable();

        $habit = Habit::create('A habit to complete', Period::Monthly, 4);
        $habitOutput = HabitOutput::fromDomain($habit);
        $uriVariables = [
            'id' => $habit->getId(),
        ];

        $completeHabitProcessor = new CompleteHabitProcessor($completeHabit);
        $habit->complete($now);

        $completeHabit
            ->expects($this->once())
            ->method('execute')
            ->with(Uuid::fromString($habit->getId()), $this->isInstanceOf(DateTimeImmutable::class))
            ->willReturn($habit);

        // Act
        $result = $completeHabitProcessor->process($habitOutput, new Post(), $uriVariables);

        $this->assertInstanceOf(HabitOutput::class, $result);
    }

    #[Test]
    public function it_throws_not_found_when_habit_does_not_exist()
    {
        $completeHabit = $this->createMock(CompleteHabit::class);

        $completeHabitProcessor = new CompleteHabitProcessor($completeHabit);

        $uuid = Uuid::v4();

        $uriVariables = [
            'id' => $uuid->toString(),
        ];

        $completeHabit
            ->expects($this->once())
            ->method('execute')
            ->with($uuid, $this->isInstanceOf(DateTimeImmutable::class))
            ->willThrowException(new \RuntimeException('Habit not found: ' . $uuid));
        ;

        $this->expectException(NotFoundHttpException::class);

        // Act
        // If Habit not found, we get a null from the HabitProvider
        $completeHabitProcessor->process(null, new Post(), $uriVariables);
    }
}
