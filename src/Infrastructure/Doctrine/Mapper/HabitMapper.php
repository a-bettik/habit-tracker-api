<?php

namespace App\Infrastructure\Doctrine\Mapper;

use App\Domain\Habit\Habit;
use App\Domain\Habit\Period;
use App\Infrastructure\Doctrine\Entity\HabitEntity;

class HabitMapper
{
    public function toDomain(HabitEntity $entity): Habit
    {
        return Habit::fromPersistence(
            $entity->getId(),
            $entity->getLabel(),
            Period::from($entity->getPeriod()),
            $entity->getTargetCount(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
        );
    }

    public function toEntity(Habit $habit): HabitEntity
    {
        $entity = new HabitEntity();

        $entity->setLabel($habit->getLabel());
        $entity->setPeriod($habit->getPeriod()->value);
        $entity->setTargetCount($habit->getTargetCount());

        return $entity;
    }

    public function updateEntity(Habit $habit, HabitEntity $entity): void
    {
        $entity->setLabel($habit->getLabel());
        $entity->setPeriod($habit->getPeriod()->value);
        $entity->setTargetCount($habit->getTargetCount());
    }
}
