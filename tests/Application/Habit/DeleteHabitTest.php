<?php

namespace App\Tests\Application\Habit;

use App\Application\Habit\DeleteHabit;
use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitRepository;
use App\Domain\Habit\Period;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class DeleteHabitTest extends TestCase
{
    private function getMockHabitRepository()
    {
        return $this->createMock(HabitRepository::class);
    }

    #[Test]
    public function it_deletes_habit()
    {
        $habitRepository = $this->getMockHabitRepository();
        $habit = Habit::create('My habit that will be deleted', Period::Daily, 1);

        // Expects get() and delete() to be called once
        $habitRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn($habit);

        $habitRepository
            ->expects($this->once())
            ->method('delete')
            ->with($this->isInstanceOf(Habit::class));

        $useCase = new DeleteHabit($habitRepository);

        $useCase->execute($habit->getId());
    }

    #[Test]
    public function it_does_not_delete_non_existing_habit()
    {
        $habitRepository = $this->getMockHabitRepository();

        // Expects get() to be called but not delete(). Instead will throw an Exception.
        $habitRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);

        $habitRepository
            ->expects($this->never())
            ->method('delete');

        $this->expectException(\RuntimeException::class);

        $useCase = new DeleteHabit($habitRepository);

        $useCase->execute(Uuid::v4());
    }
}