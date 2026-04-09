<?php

namespace App\Tests\Domain;

use App\Domain\Habit\Habit;
use App\Domain\Habit\Period;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HabitTest extends TestCase
{
    #[Test]
    public function it_creates_a_habit_with_defaults(): void
    {
        $habit = Habit::create('Meditate');

        $this->assertSame('Meditate', $habit->getLabel());
        $this->assertSame(Period::Daily, $habit->getPeriod()); // This is the default period
        $this->assertSame(1, $habit->getTargetCount()); // This is the default target count

        // The habit should have an ID and timestamps
        $this->assertNotNull($habit->getId());
        $this->assertNotNull($habit->getCreatedAt());
        $this->assertNotNull($habit->getUpdatedAt());

        // For now the habit should not have any HabitLog
        $this->assertEmpty($habit->getHabitLogs());
    }

    #[Test]
    public function it_updates_only_given_fields(): void
    {
        $habit = Habit::create('Read a book');

        $originalUpdatedAt = $habit->getUpdatedAt();
        sleep(1); // Ensures timestamp difference

        $habit->update(label: 'Read a page of a book');
        $habit->update(period: Period::Daily);

        $this->assertGreaterThan($originalUpdatedAt, $habit->getUpdatedAt());
    }

    #[Test]
    public function it_completes_a_habit(): void
    {
        $habit = Habit::create('Read a book');

        $now = new \DateTimeImmutable();
        $habit->complete($now);

        $this->assertTrue($habit->isCompletedOn($now));
        $this->assertCount(1, $habit->getHabitLogs()); // We should have only one habit
    }

    #[Test]
    public function it_cannot_complete_a_habit_twice_a_day(): void
    {
        $habit = Habit::create('Read a book');

        $now = new \DateTimeImmutable();
        $yesterday = $now->modify('-1 day');

        $habit->complete($now);
        $habit->complete($yesterday);

        $this->assertTrue($habit->isCompletedOn($now));
        $this->assertCount(2, $habit->getHabitLogs()); // We should have only one habit

        $this->expectException(\DomainException::class);
        $habit->complete($now); // This should trigger an exception
    }


    #[Test]
    public function it_detects_completion_on_specific_day(): void
    {
        $habit = Habit::create('Read a book');

        $now = new \DateTimeImmutable();
        $yesterday = $now->modify('-1 day');

        $habit->complete($now);

        $this->assertTrue($habit->isCompletedOn($now));
        $this->assertFalse($habit->isCompletedOn($yesterday));
    }
}