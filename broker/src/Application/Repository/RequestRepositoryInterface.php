<?php

namespace Broker\Repository;

interface RequestRepositoryInterface
{
    /**
     * Returns request by the given id.
     */
    public function findOneById(int $id): array;

    /**
     * Updates the request data by the given message
     */
    public function update(int $id, string $message): void;

    /**
     * Creates a row in requests table with the given message.
     */
    public function create(string $message): string;
}
