<?php

declare(strict_types=1);

namespace ServiceB;

final class ConfigResolver
{
    /**
     * @return array<string, string>
     */
    public static function resolve(string $service): array
    {
        return require __DIR__ . "/../../config/{$service}.php";
    }
}
