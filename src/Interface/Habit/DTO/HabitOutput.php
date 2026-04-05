<?php

namespace App\Interface\Habit\DTO;

use App\Domain\Habit\Habit;
use Symfony\Component\Uid\Uuid;

final class HabitOutput
{
    public Uuid $id;
    public string $label;
    public string $period;
    public int $targetCount;
    public \DateTimeImmutable $createdAt;
    public \DateTimeImmutable $updatedAt;

    public static function fromDomain(Habit $habit): self
    {
        $output = new self();
        $output->id = $habit->getId();
        $output->label = $habit->getLabel();
        $output->period = $habit->getPeriod()->value;
        $output->targetCount = $habit->getTargetCount();
        $output->createdAt = $habit->getCreatedAt();
        $output->updatedAt = $habit->getUpdatedAt();
        return $output;
    }
}