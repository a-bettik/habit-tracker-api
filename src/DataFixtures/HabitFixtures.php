<?php

namespace App\DataFixtures;

use App\Entity\Habit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HabitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $habit1 = new Habit();
        $habit1->setLabel('Drink water');

        $habit2 = new Habit();
        $habit2->setLabel('Meditate');

        $habit3 = new Habit();
        $habit3->setLabel('Exercise');

        $habit4 = new Habit();
        $habit4->setLabel('Read my book');

        $habit5 = new Habit();
        $habit5->setLabel('Work on my side project');

        $manager->persist($habit1);
        $manager->persist($habit2);
        $manager->persist($habit3);
        $manager->persist($habit4);
        $manager->persist($habit5);

        $manager->flush();
    }
}

