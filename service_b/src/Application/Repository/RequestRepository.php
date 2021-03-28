<?php

declare(strict_types=1);

namespace ServiceB\Repository;

use Doctrine\DBAL\Connection;

final class RequestRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Updates a request
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function update(int $id, string $message): void
    {
        $this->connection
            ->createQueryBuilder()
            ->update('requests')
            ->set('message', ':message')
            ->where('id = :id')
            ->setParameter('message', $message)
            ->setParameter('id', $id)
            ->execute();
    }
}
