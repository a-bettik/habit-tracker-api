<?php

namespace App\Infrastructure\Doctrine\Mapper;

use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitLog;
use App\Domain\Habit\Period;
use App\Infrastructure\Doctrine\Entity\HabitEntity;
use App\Infrastructure\Doctrine\Entity\HabitLogEntity;

class HabitMapper
{
    public function toDomain(HabitEntity $entity): Habit
    {
        $habit = Habit::fromPersistence(
            $entity->getId(),
            $entity->getLabel(),
            Period::from($entity->getPeriod()),
            $entity->getTargetCount(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
        );

        $logs = array_map(
            fn(HabitLogEntity $log) => new HabitLog($habit, $log->getDate()),
            $entity->getHabitLogs()->toArray(),
        );

        return Habit::fromPersistence(
            $entity->getId(),
            $entity->getLabel(),
            Period::from($entity->getPeriod()),
            $entity->getTargetCount(),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt(),
            $logs,
        );
    }

    public function toEntity(Habit $habit): HabitEntity
    {
        $entity = new HabitEntity($habit->getId());

        $entity->setLabel($habit->getLabel());
        $entity->setPeriod($habit->getPeriod()->value);
        $entity->setTargetCount($habit->getTargetCount());
        $entity->setCreatedAt($habit->getCreatedAt());
        $entity->setUpdatedAt($habit->getUpdatedAt());

        return $entity;
    }

    public function updateEntity(Habit $habit, HabitEntity $entity): void
    {
        $entity->setLabel($habit->getLabel());
        $entity->setPeriod($habit->getPeriod()->value);
        $entity->setTargetCount($habit->getTargetCount());
        $entity->setCreatedAt($habit->getCreatedAt());
        $entity->setUpdatedAt($habit->getUpdatedAt());

        // Sync new HabitLogs
        $existingDates = array_map(
            fn(HabitLogEntity $log) => $log->getDate()->format('Y-m-d'),
            $entity->getHabitLogs()->toArray(),
        );

        foreach ($habit->getHabitLogs() as $domainLog) {
            if (!in_array($domainLog->getDate()->format('Y-m-d'), $existingDates, true)) {
                $entity->addHabitLog(new HabitLogEntity($entity, $domainLog->getDate()));
            }
        }
    }
}
