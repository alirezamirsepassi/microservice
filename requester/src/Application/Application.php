<?php

declare(strict_types=1);

namespace Requester;

use Requester\Exception\NoResponseException;
use RuntimeException;
use Symfony\Component\HttpClient\Exception\TimeoutException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Application
{
    private int $id;

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Bootstrap the application by sending a message "Hi, " to broker
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \JsonException
     */
    public function boot(): void
    {
        echo "Sending message \"Hi, \" to the broker ...\n";

        $response = $this->client->request(
            'POST',
            '/requests',
            [
                'json' => ['message' => 'Hi, '],
            ]
        );

        $this->id = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR)['id'];
    }

    /**
     * Request to broker with 1s timout to get response
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Requester\Exception\NoResponseException
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \RuntimeException
     */
    public function __invoke(): void
    {
        if (! $this->id) {
            throw new RuntimeException('The application is not bootstrapped yet!');
        }

        try {
            $response = $this->client->request('GET', "/requests/{$this->id}", ['timeout' => 1]);

            echo "Response from broker: {$response->getContent()}\n";
        } catch (TimeoutException $e) {
            throw new NoResponseException();
        }
    }
}
