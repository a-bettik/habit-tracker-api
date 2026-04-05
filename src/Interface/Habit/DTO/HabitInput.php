<?php

namespace App\Interface\Habit\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class HabitInput
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $label;

    #[Assert\Choice(choices: ['daily', 'weekly', 'monthly', 'yearly'])]
    public string $period = 'daily';

    #[Assert\Positive]
    public int $targetCount = 1;
}
