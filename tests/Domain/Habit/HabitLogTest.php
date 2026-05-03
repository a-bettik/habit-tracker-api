<?php

namespace App\Tests\Domain\Habit;

use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitLog;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class HabitLogTest extends TestCase
{
    #[Test]
    public function it_detects_it_is_the_same_day()
    {
        $habit = Habit::create('Meditate');
        $date1 = new \DateTimeImmutable('2026-01-01 00:00:00');
        $date2 = new \DateTimeImmutable('2026-01-01 23:59:59');
        $habitLog = new HabitLog($habit, $date1);
        $this->assertTrue($habitLog->isSameDay($date2));
    }

    #[Test]
    public function it_detects_not_the_same_day()
    {
        $habit = Habit::create('Meditate');
        $date1 = new \DateTimeImmutable('2026-01-01 23:23:59');
        $date2 = new \DateTimeImmutable('2026-01-02 00:00:00');
        $habitLog = new HabitLog($habit, $date1);
        $this->assertFalse($habitLog->isSameDay($date2));
    }
}