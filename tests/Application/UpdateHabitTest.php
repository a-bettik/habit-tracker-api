<?php

namespace App\Tests\Application;

use App\Application\Habit\CreateHabit;
use App\Application\Habit\UpdateHabit;
use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitRepository;
use App\Domain\Habit\Period;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Uid\Uuid;

class UpdateHabitTest extends TestCase
{
    private function getMockHabitRepository()
    {
        return $this->createMock(HabitRepository::class);
    }

    #[Test]
    public function it_updates_habit()
    {
        $habitRepository = $this->getMockHabitRepository();
        $habit = Habit::create('Old habit label', Period::Daily, 1);

        // Expects get() and save() to be called once
        $habitRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn($habit);

        $habitRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Habit::class));

        $useCase = new UpdateHabit($habitRepository);

        $loadedHabit = $useCase->execute($habit->getId(), 'New habit label', Period::Weekly, 3);

        $this->assertSame('New habit label', $loadedHabit->getLabel());
        $this->assertSame(Period::Weekly, $loadedHabit->getPeriod());
        $this->assertSame(3, $loadedHabit->getTargetCount());
    }

    #[Test]
    public function it_updates_habit_with_default_params()
    {
        $habitRepository = $this->getMockHabitRepository();
        $habit = Habit::create('Old habit label', Period::Monthly, 10);

        // Expects get() and save() to be called once
        $habitRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn($habit);

        $habitRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Habit::class));

        $useCase = new UpdateHabit($habitRepository);

        $loadedHabit = $useCase->execute($habit->getId(), 'New habit label');

        $this->assertSame('New habit label', $loadedHabit->getLabel());
        $this->assertSame(Period::Daily, $loadedHabit->getPeriod());
        $this->assertSame(1, $loadedHabit->getTargetCount());
    }

    #[Test]
    public function it_does_not_update_if_habit_not_found()
    {
        $habitRepository = $this->getMockHabitRepository();

        $habitRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);

        $habitRepository
            ->expects($this->never())
            ->method('save');

        $this->expectException(\RuntimeException::class);

        $useCase = new UpdateHabit($habitRepository);
        $useCase->execute(Uuid::v4(), 'New label');
    }
}