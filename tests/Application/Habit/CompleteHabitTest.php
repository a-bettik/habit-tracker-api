<?php

namespace App\Tests\Application\Habit;

use App\Application\Habit\CompleteHabit;
use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitRepository;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CompleteHabitTest extends TestCase
{
    private function getMockHabitRepository()
    {
        return $this->createMock(HabitRepository::class);
    }

    #[Test]
    public function it_completes_habit()
    {
        $habitRepository = $this->getMockHabitRepository();

        $now = new \DateTimeImmutable();
        $habit = Habit::create('Water the plants');

        // CompleteHabit should call save()
        $habitRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Habit::class));

        // Repository should return a habit
        $habitRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn($habit);

        $useCase = new CompleteHabit($habitRepository);
        $useCase->execute($habit->getId(), $now);

        $this->assertTrue($habit->isCompletedOn($now));
    }
    
    #[Test]
    public function it_does_not_complete_unexisting_habit()
    {
        $habitRepository = $this->getMockHabitRepository();

        $habitRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);

        $useCase = new CompleteHabit($habitRepository);
        $useCase->execute(Uuid::v4(), new DateTimeImmutable());
    }

}
