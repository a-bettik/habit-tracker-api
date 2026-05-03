<?php

namespace App\Tests\Provider;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitRepository;
use App\Domain\Habit\Period;
use App\Interface\Habit\DTO\HabitOutput;
use App\Interface\Habit\Provider\HabitProvider;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Uid\Uuid;

class HabitProviderTest extends TestCase
{
    private function getMockHabitRepository()
    {
        return $this->createMock(HabitRepository::class);
    }

    #[Test]
    public function it_provides_collection_of_habits()
    {
        $habitRepository = $this->getMockHabitRepository();

        $habitProvider = new HabitProvider($habitRepository);
        $habitRepository
            ->expects($this->once()) // I should have at least one Habit
            ->method('findAll')
            ->willReturn([
                Habit::create('The first habit', Period::Daily, 1),
                Habit::create('The second habit', Period::Weekly, 2),
                Habit::create('The third habit', Period::Monthly, 3),
            ]);

        // Act
        $result = $habitProvider->provide(new GetCollection());

        $this->assertIsArray($result);
        foreach($result as $habitOutput) {
            $this->assertInstanceOf(HabitOutput::class, $habitOutput);
        }
    }

    #[Test]
    public function it_provide_single_habit()
    {
        $habitRepository = $this->getMockHabitRepository();

        $habit = Habit::create('The habit to fetch', Period::Monthly, 10);

        $habitProvider = new HabitProvider($habitRepository);
        $uriVariables = [
            'id' => $habit->getId()->toString(),
        ];
        $habitRepository
            ->expects($this->once())
            ->method('get')
            ->with($habit->getId())
            ->willReturn($habit);

        // Act
        $result = $habitProvider->provide(new Get(), $uriVariables);

        $this->assertInstanceOf(HabitOutput::class, $result);
    }

    #[Test]
    public function it_returns_null_when_habit_not_found()
    {
        $habitRepository = $this->getMockHabitRepository();

        $habitProvider = new HabitProvider($habitRepository);
        $uuid = Uuid::v4();
        $uriVariables = [
            'id' => $uuid->toString(),
        ];
        $habitRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn(null);

        // Act
        $result = $habitProvider->provide(new Get(), $uriVariables);

        $this->assertNull($result);
    }
}