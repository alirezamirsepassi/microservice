<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Configure http client
$client = \Symfony\Component\HttpClient\HttpClient::create(\Requester\ConfigResolver::resolve('http'));

$app = new \Requester\Application($client);
$loop = React\EventLoop\Factory::create();

$app->boot();

$loop->addPeriodicTimer(0.05, $app);
$loop->run();
