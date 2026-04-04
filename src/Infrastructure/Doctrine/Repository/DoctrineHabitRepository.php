<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitRepository;
use App\Infrastructure\Doctrine\Entity\HabitEntity;
use App\Infrastructure\Doctrine\Mapper\HabitMapper;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineHabitRepository implements HabitRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HabitMapper $mapper,
    ) {}
    public function save(Habit $habit): void
    {
        $entity = $this->entityManager->find(HabitEntity::class, $habit->getId());

        if (!$entity) {
            // Creation
            $entity = $this->mapper->toEntity($habit);
            $this->entityManager->persist($entity);
        } else {
            // Update
            $this->mapper->updateEntity($habit, $entity);
        }
        $this->entityManager->flush();
    }

    public function get(int $id): ?Habit
    {
        $entity = $this->entityManager->find(HabitEntity::class, $id);
        return $entity ? $this->mapper->toDomain($entity) : null;
    }
}
