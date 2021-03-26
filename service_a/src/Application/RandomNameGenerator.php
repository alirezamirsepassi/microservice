<?php

namespace ServiceA;

final class RandomNameGenerator implements GeneratorInterface
{
    private array $names = [
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
        return $this->names[array_rand($this->names)];
    }
}
