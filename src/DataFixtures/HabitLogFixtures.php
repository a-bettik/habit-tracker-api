<?php

namespace App\DataFixtures;

use App\Entity\Habit;
use App\Entity\HabitLog;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HabitLogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Get my habits
        $drinkWaterHabit = $this->getReference(HabitFixtures::HABIT_DRINK_WATER, Habit::class);
        $meditateHabit = $this->getReference(HabitFixtures::HABIT_MEDITATE, Habit::class);
        $exerciseHabit = $this->getReference(HabitFixtures::HABIT_EXERCISE, Habit::class);
        $readyMyBookHabit = $this->getReference(HabitFixtures::HABIT_READ_MY_BOOK, Habit::class);
        $workOnMyProjectHabit = $this->getReference(HabitFixtures::HABIT_WORK_ON_MY_SIDE_PROJECT, Habit::class);

        $now = new \DateTimeImmutable();

        // For the last 90 days:
        for ($i = 90; $i >= 0; $i--) {

            // We drank water almost every day (90% of the time)
            if (random_int(0, 100) <= 90) {
                $habitLog = new HabitLog();
                $habitLog->setHabit($drinkWaterHabit);
                $habitLog->setDate($now->modify("-{$i} day")->setTime(rand(0, 23), rand(0, 59), rand(0, 59)));
                $manager->persist($habitLog);
            }

            // We meditated almost every day (80% of the time)
            if (random_int(0, 100) <= 80) {
                $habitLog = new HabitLog();
                $habitLog->setHabit($meditateHabit);
                $habitLog->setDate($now->modify("-{$i} day"));
                $habitLog->setDate($now->modify("-{$i} day")->setTime(rand(0, 23), rand(0, 59), rand(0, 59)));
                $manager->persist($habitLog);
            }

            // We exercised about 1/8 of the time
            if (random_int(1, 8) === 1) {
                $habitLog = new HabitLog();
                $habitLog->setHabit($exerciseHabit);
                $habitLog->setDate($now->modify("-{$i} day"));
                $habitLog->setDate($now->modify("-{$i} day")->setTime(rand(0, 23), rand(0, 59), rand(0, 59)));
                $manager->persist($habitLog);
            }

            // We read our book about 2/7 of the time
            if (random_int(1, 7) <= 2) {
                $habitLog = new HabitLog();
                $habitLog->setHabit($readyMyBookHabit);
                $habitLog->setDate($now->modify("-{$i} day"));
                $habitLog->setDate($now->modify("-{$i} day")->setTime(rand(0, 23), rand(0, 59), rand(0, 59)));
                $manager->persist($habitLog);
            }

            // We worked on our project about 2/7 of the time
            if (random_int(1, 7) <= 2) {
                $habitLog = new HabitLog();
                $habitLog->setHabit($workOnMyProjectHabit);
                $habitLog->setDate($now->modify("-{$i} day"));
                $habitLog->setDate($now->modify("-{$i} day")->setTime(rand(0, 23), rand(0, 59), rand(0, 59)));
                $manager->persist($habitLog);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [HabitFixtures::class];
    }
}