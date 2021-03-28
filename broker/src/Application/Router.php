<?php

declare(strict_types=1);

namespace Broker;

use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use LogicException;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

final class Router
{
    private GroupCountBased $dispatcher;

    public function __construct(RouteCollector $routes)
    {
        $this->dispatcher = new GroupCountBased($routes->getData());
    }

    /**
     * Controller resolver
     */
    public function __invoke(ServerRequestInterface $request): Response
    {
        $route = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($route[0]) {
            case Dispatcher::NOT_FOUND:
                return new Response(404, ['Content-Type' => 'text/plain'], 'Not found');
            case Dispatcher::METHOD_NOT_ALLOWED:
                return new Response(405, ['Content-Type' => 'text/plain'], 'Method not allowed');
            case Dispatcher::FOUND:
                $params = $route[2];

                return $route[1]($request, ...array_values($params));
        }

        throw new LogicException('Something wrong with routing!');
    }
}
