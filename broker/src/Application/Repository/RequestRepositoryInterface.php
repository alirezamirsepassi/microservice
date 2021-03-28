<?php

declare(strict_types=1);

namespace Broker\Repository;

interface RequestRepositoryInterface
{
    /**
     * Returns request by the given id.
     *
     * @return array<string, string>
     */
    public function findOneById(int $id): array;

    /**
     * Updates the request data by the given message
     */
    public function update(int $id, string $message): void;

    /**
     * Creates a row in requests table with the given message.
     */
    public function create(string $message): int;
}
