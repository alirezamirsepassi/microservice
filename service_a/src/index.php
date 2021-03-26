<?php

require __DIR__ . '/../vendor/autoload.php';

// Configure Kafka
$factory = new \Enqueue\RdKafka\RdKafkaConnectionFactory(
    [
        'global' => [
            'group.id'             => 'service_a',
            'metadata.broker.list' => 'kafka:9092',
            'enable.auto.commit'   => 'false',
        ],
        'topic'  => [
            'auto.offset.reset'        => 'beginning',
            'allow.auto.create.topics' => 'true',
        ],
    ]
);

// Configure name generator
$generator = new \ServiceA\RandomNameGenerator();

// Configure http client
$client = \Symfony\Component\HttpClient\HttpClient::create();

$context = $factory->createContext();
$topic = $context->createTopic('Topic_A');
$consumer = $context->createConsumer($topic);

while ($message = $consumer->receive()) {
    echo "Appending a random name to message {$message->getBody()} on topic A\n";

    // Update message with random generated name
    try {
        $client->request(
            'PATCH',
            "http://broker/requests/{$message->getProperty('id')}",
            ['json' => ['message' => "{$message->getBody()}{$generator->generate()}"]]
        );
        $consumer->acknowledge($message);
    } catch (\Exception $e) {
        $consumer->reject($message);
    }
}
