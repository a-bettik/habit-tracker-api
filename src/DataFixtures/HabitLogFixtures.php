<?php

namespace App\DataFixtures;

use App\Application\Habit\CompleteHabit;
use App\Domain\Habit\Habit;
use App\Infrastructure\Doctrine\Entity\HabitEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HabitLogFixtures extends Fixture
{
    public function __construct(
        private CompleteHabit $completeHabit,
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Get my habits
        $drinkWaterHabit = $this->getReference(HabitFixtures::HABIT_DRINK_WATER, HabitEntity::class);
        $meditateHabit = $this->getReference(HabitFixtures::HABIT_MEDITATE, HabitEntity::class);
        $exerciseHabit = $this->getReference(HabitFixtures::HABIT_EXERCISE, HabitEntity::class);
        $readyMyBookHabit = $this->getReference(HabitFixtures::HABIT_READ_MY_BOOK, HabitEntity::class);
        $workOnMyProjectHabit = $this->getReference(HabitFixtures::HABIT_WORK_ON_MY_SIDE_PROJECT, HabitEntity::class);

        $now = new \DateTimeImmutable();

        // For the last 90 days:
        for ($i = 90; $i >= 0; $i--) {
            $date = $now->modify("-{$i} day")->setTime(rand(0, 23), rand(0, 59), rand(0, 59));

            // We drank water almost every day (90% of the time)
            if (random_int(0, 100) <= 90) {
                $this->completeHabit->execute($drinkWaterHabit->getId(), $date);
            }

            // We meditated almost every day (80% of the time)
            if (random_int(0, 100) <= 80) {
                $this->completeHabit->execute($meditateHabit->getId(), $date);
            }

            // We exercised about 1/8 of the time
            if (random_int(1, 8) === 1) {
                $this->completeHabit->execute($exerciseHabit->getId(), $date);
            }

            // We read our book about 2/7 of the time
            if (random_int(1, 7) <= 2) {
                $this->completeHabit->execute($readyMyBookHabit->getId(), $date);
            }

            // We worked on our project about 2/7 of the time
            if (random_int(1, 7) <= 2) {
                $this->completeHabit->execute($workOnMyProjectHabit->getId(), $date);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [HabitFixtures::class];
    }
}