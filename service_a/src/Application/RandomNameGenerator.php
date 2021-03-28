<?php

declare(strict_types=1);

namespace ServiceA;

final class RandomNameGenerator implements GeneratorInterface
{
    private const NAMES = [
        'Joao',
        'Bram',
        'Gabriel',
        'Fehim',
        'Eni',
        'Patrick',
        'Micha',
        'Mirzet',
        'Liliana',
        'Sebastien',
    ];

    public function generate(): string
    {
        return self::NAMES[array_rand(self::NAMES)];
    }
}
