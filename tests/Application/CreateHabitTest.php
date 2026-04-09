<?php

namespace App\Tests\Application;

use App\Application\Habit\CreateHabit;
use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitRepository;
use App\Domain\Habit\Period;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CreateHabitTest extends TestCase
{
    private function getMockHabitRepository()
    {
        return $this->createMock(HabitRepository::class);
    }

    #[Test]
    public function it_creates_habit()
    {
        $habitRepository = $this->getMockHabitRepository();

        // Expects save() to be called once
        $habitRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Habit::class));

        $useCase = new CreateHabit($habitRepository);

        $habit = $useCase->execute('Read a page of a book', Period::Weekly, 5);
        $this->assertInstanceOf(Habit::class, $habit);
        $this->assertSame('Read a page of a book', $habit->getLabel());
    }
    
    #[Test]
    public function it_creates_habit_with_default_params()
    {
        $habitRepository = $this->getMockHabitRepository();

        // Expects save() to be called once
        $habitRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Habit::class));

        $useCase = new CreateHabit($habitRepository);

        $habit = $useCase->execute('Read a page of a book');
        $this->assertInstanceOf(Habit::class, $habit);
        $this->assertSame(Period::Daily, $habit->getPeriod());
        $this->assertSame(1, $habit->getTargetCount());
    }
}