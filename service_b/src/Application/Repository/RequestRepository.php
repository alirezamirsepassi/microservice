<?php

namespace ServiceB\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

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
     * @param int    $id
     * @param string $message
     *
     * @return void
     * @throws Exception
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
