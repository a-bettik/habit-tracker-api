<?php

namespace App\DataFixtures;

use App\Entity\Habit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HabitFixtures extends Fixture
{
    public const HABIT_DRINK_WATER = 'drink_water';
    public const HABIT_MEDITATE = 'meditate';
    public const HABIT_EXERCISE = 'exercise';
    public const HABIT_READ_MY_BOOK = 'read_my_book';
    public const HABIT_WORK_ON_MY_SIDE_PROJECT = 'work_on_my_side_project';
    public const HABITS = [
        self::HABIT_DRINK_WATER,
        self::HABIT_MEDITATE,
        self::HABIT_EXERCISE,
        self::HABIT_READ_MY_BOOK,
        self::HABIT_WORK_ON_MY_SIDE_PROJECT,
    ];

    public function load(ObjectManager $manager): void
    {
        // Drink water every day
        $habit1 = new Habit();
        $habit1->setLabel('Drink water');
        $habit1->setPeriod(Habit::PERIOD_DAILY);
        $habit1->setTargetCount(1);
        $this->addReference(self::HABIT_DRINK_WATER, $habit1);

        // Meditate every day
        $habit2 = new Habit();
        $habit2->setLabel('Meditate');
        $habit2->setPeriod(Habit::PERIOD_DAILY);
        $habit2->setTargetCount(1);
        $this->addReference(self::HABIT_MEDITATE, $habit2);

        // Exercise weekly
        $habit3 = new Habit();
        $habit3->setLabel('Exercise');
        $habit3->setPeriod(Habit::PERIOD_WEEKLY);
        $habit3->setTargetCount(1);
        $this->addReference(self::HABIT_EXERCISE, $habit3);

        // Ready my book twice a week
        $habit4 = new Habit();
        $habit4->setLabel('Read my book');
        $habit4->setPeriod(Habit::PERIOD_WEEKLY);
        $habit4->setTargetCount(2);
        $this->addReference(self::HABIT_READ_MY_BOOK, $habit4);

        // Work on my project six times a month
        $habit5 = new Habit();
        $habit5->setLabel('Work on my side project');
        $habit5->setPeriod(Habit::PERIOD_MONTHLY);
        $habit5->setTargetCount(6);
        $this->addReference(self::HABIT_WORK_ON_MY_SIDE_PROJECT, $habit5);

        $manager->persist($habit1);
        $manager->persist($habit2);
        $manager->persist($habit3);
        $manager->persist($habit4);
        $manager->persist($habit5);

        $manager->flush();
    }
}

