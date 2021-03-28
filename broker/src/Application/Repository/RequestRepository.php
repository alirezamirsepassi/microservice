<?php

declare(strict_types=1);

namespace Broker\Repository;

use Doctrine\DBAL\Connection;

final class RequestRepository implements RequestRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function create(string $message): int
    {
        $this->connection
            ->createQueryBuilder()
            ->insert('requests')
            ->setValue('message', ':message')
            ->setParameter('message', $message)
            ->execute();

        return (int) $this->connection->lastInsertId();
    }

    /**
     * @inheritDoc
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

    /**
     * @inheritDoc
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function findOneById(int $id): array
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('requests')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->execute()
            ->fetchAssociative();
    }
}
