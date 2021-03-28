<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Configure Kafka
$factory = new \Enqueue\RdKafka\RdKafkaConnectionFactory(\ServiceB\ConfigResolver::resolve('kafka'));

$context = $factory->createContext();
$topic = $context->createTopic('Topic_B');
$consumer = $context->createConsumer($topic);

$repository = new \ServiceB\Repository\RequestRepository(
    \Doctrine\DBAL\DriverManager::getConnection(['url' => 'pgsql://hellouser:hellopass@database/hellodb'])
);

while ($message = $consumer->receive()) {
    echo "Appending \"Bye\" to message {$message->getBody()} on topic B\n";

    // Update message
    try {
        $repository->update((int) $message->getProperty('id'), "{$message->getBody()} Bye");

        $consumer->acknowledge($message);
    } catch (\Exception $e) {
        $consumer->reject($message);
    }
}
