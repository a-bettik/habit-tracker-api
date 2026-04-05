<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Habit\Habit;
use App\Domain\Habit\HabitRepository;
use App\Infrastructure\Doctrine\Entity\HabitEntity;
use App\Infrastructure\Doctrine\Mapper\HabitMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class DoctrineHabitRepository implements HabitRepository
{
    public function __construct(
        private HabitMapper $mapper,
        private EntityManagerInterface $entityManager,
    ) {}

    public function save(Habit $habit): void
    {
        $em = $this->entityManager;
        $entity = $em->find(HabitEntity::class, $habit->getId());

        if (!$entity) {
            // Creation
            $entity = $this->mapper->toEntity($habit);
            $em->persist($entity);
        } else {
            // Update
            $this->mapper->updateEntity($habit, $entity);
        }

        $em->persist($entity);
        $em->flush();
    }

    public function get(Uuid $id): ?Habit
    {
        $entity = $this->entityManager->find(HabitEntity::class, $id);
        return $entity ? $this->mapper->toDomain($entity) : null;
    }
}
