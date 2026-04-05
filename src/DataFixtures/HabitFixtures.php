<?php

namespace App\DataFixtures;

use App\Application\Habit\CreateHabit;
use App\Domain\Habit\HabitRepository;
use App\Domain\Habit\Period;
use App\Infrastructure\Doctrine\Entity\HabitEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class HabitFixtures extends Fixture
{
    public const HABIT_DRINK_WATER = 'drink_water';
    public const HABIT_MEDITATE = 'meditate';
    public const HABIT_EXERCISE = 'exercise';
    public const HABIT_READ_MY_BOOK = 'read_my_book';
    public const HABIT_WORK_ON_MY_SIDE_PROJECT = 'work_on_my_side_project';

    public function __construct(
        private CreateHabit $createHabit,
        private HabitRepository $habitRepository,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $habits = [
            self::HABIT_DRINK_WATER             => $this->createHabit->execute('Drink water', Period::Daily, 1),
            self::HABIT_MEDITATE                => $this->createHabit->execute('Meditate', Period::Daily, 1),
            self::HABIT_EXERCISE                => $this->createHabit->execute('Exercise', Period::Weekly, 1),
            self::HABIT_READ_MY_BOOK            => $this->createHabit->execute('Read my book', Period::Weekly, 2),
            self::HABIT_WORK_ON_MY_SIDE_PROJECT => $this->createHabit->execute('Work on my side project', Period::Monthly, 6),
        ];

        foreach ($habits as $reference => $habit) {
            $entity = $manager->find(HabitEntity::class, $habit->getId()->toString());
            $this->addReference($reference, $entity);
        }
    }
}
