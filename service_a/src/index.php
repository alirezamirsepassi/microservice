<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Configure Kafka
$factory = new \Enqueue\RdKafka\RdKafkaConnectionFactory(\ServiceA\ConfigResolver::resolve('kafka'));

// Configure name generator
$generator = new \ServiceA\RandomNameGenerator();

// Configure http client
$client = \Symfony\Component\HttpClient\HttpClient::create(\ServiceA\ConfigResolver::resolve('http'));

$context = $factory->createContext();
$topic = $context->createTopic('Topic_A');
$consumer = $context->createConsumer($topic);

while ($message = $consumer->receive()) {
    echo "Appending a random name to message {$message->getBody()} on topic A\n";

    // Update message with a random generated name
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
