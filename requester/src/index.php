<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Requester\Application();
$loop = React\EventLoop\Factory::create();

$app->boot();
$timer = $loop->addPeriodicTimer(0.05, $app);

$loop->run();
