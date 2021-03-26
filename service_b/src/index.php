<?php

require __DIR__ . '/../vendor/autoload.php';

// Configure Kafka
$factory = new \Enqueue\RdKafka\RdKafkaConnectionFactory(
    [
        'global' => [
            'group.id'             => 'service_b',
            'metadata.broker.list' => 'kafka:9092',
            'enable.auto.commit'   => 'false',
        ],
        'topic'  => [
            'auto.offset.reset'        => 'beginning',
            'allow.auto.create.topics' => 'true',
        ],
    ]
);


$context = $factory->createContext();

$queue = $context->createQueue('Topic_B');

$consumer = $context->createConsumer($queue);

$repository = new \ServiceB\Repository\RequestRepository(
    \Doctrine\DBAL\DriverManager::getConnection(['url' => 'pgsql://hellouser:hellopass@database/hellodb'])
);


while ($message = $consumer->receive()) {
    echo "Appending \"Bye\" to message {$message->getBody()} on topic B\n";

    // Update message
    try {
        $repository->update($message->getProperty('id'), "{$message->getBody()} Bye");
        $consumer->acknowledge($message);
    } catch (\Exception $e) {
        $consumer->reject($message);
    }
}
