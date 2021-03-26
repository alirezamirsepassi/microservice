<?php

require __DIR__ . '/../vendor/autoload.php';

// Configure Kafka
$factory = new \Enqueue\RdKafka\RdKafkaConnectionFactory([
    'global' => [
        'group.id' => uniqid('', true),
        'metadata.broker.list' => 'kafka:9092',
        'enable.auto.commit' => 'false',
    ],
    'topic' => [
        'auto.offset.reset' => 'beginning',
    ],
]);


// Create the controller
$controller = new \Broker\Controller\RequestController(
    new \Broker\Repository\RequestRepository(
        \Doctrine\DBAL\DriverManager::getConnection(['url' => 'pgsql://hellouser:hellopass@database/hellodb'])
    ),
    $factory->createContext()
);

// Register routes
$routes = new \FastRoute\RouteCollector(
    new \FastRoute\RouteParser\Std(),
    new \FastRoute\DataGenerator\GroupCountBased()
);
$routes->post('/requests', [$controller, 'create']);
$routes->patch('/requests/{id:\d+}', [$controller, 'update']);
$routes->get('/requests/{id:\d+}', [$controller, 'show']);


// Create a react PHP server
$loop = React\EventLoop\Factory::create();

$server = new React\Http\Server($loop, new \Broker\Router($routes));

$socket = new React\Socket\Server('0.0.0.0:80', $loop);
$server->listen($socket);

echo 'Listening on ' . str_replace('tcp:', 'http:', $socket->getAddress()) . "\n";
$loop->run();
