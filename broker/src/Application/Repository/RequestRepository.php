<?php

namespace Broker\Repository;

use Doctrine\DBAL\Connection;

/**
 * Class RequestRepository
 *
 * @package Broker\Repository
 */
final class RequestRepository implements RequestRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function create(string $message): string
    {
        $this->connection
            ->createQueryBuilder()
            ->insert('requests')
            ->setValue('message', ':message')
            ->setParameter('message', $message)
            ->execute();

        return $this->connection->lastInsertId();
    }

    /**
     * @inheritDoc
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
