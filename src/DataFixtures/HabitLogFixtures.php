<?php

namespace App\DataFixtures;

use App\Infrastructure\Doctrine\Entity\HabitEntity;
use App\Infrastructure\Doctrine\Entity\HabitLogEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HabitLogFixtures extends Fixture
{
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

            // We drank water almost every day (90% of the time)
            if (random_int(0, 100) <= 90) {
                $log = new HabitLogEntity($drinkWaterHabit, $now->modify("-{$i} day")->setTime(rand(0, 23), rand(0, 59), rand(0, 59)));
                $manager->persist($log);
            }

            // We meditated almost every day (80% of the time)
            if (random_int(0, 100) <= 80) {
                $log = new HabitLogEntity($meditateHabit, $now->modify("-{$i} day")->setTime(rand(0, 23), rand(0, 59), rand(0, 59)));
                $manager->persist($log);
            }

            // We exercised about 1/8 of the time
            if (random_int(1, 8) === 1) {
                $log = new HabitLogEntity($exerciseHabit, $now->modify("-{$i} day")->setTime(rand(0, 23), rand(0, 59), rand(0, 59)));
                $manager->persist($log);
            }

            // We read our book about 2/7 of the time
            if (random_int(1, 7) <= 2) {
                $log = new HabitLogEntity($readyMyBookHabit, $now->modify("-{$i} day")->setTime(rand(0, 23), rand(0, 59), rand(0, 59)));
                $manager->persist($log);
            }

            // We worked on our project about 2/7 of the time
            if (random_int(1, 7) <= 2) {
                $log = new HabitLogEntity($workOnMyProjectHabit, $now->modify("-{$i} day")->setTime(rand(0, 23), rand(0, 59), rand(0, 59)));
                $manager->persist($log);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [HabitFixtures::class];
    }
}