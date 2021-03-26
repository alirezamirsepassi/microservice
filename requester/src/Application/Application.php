<?php

namespace Requester;

use Exception;
use Requester\Exception\NoResponseException;
use Symfony\Component\HttpClient\Exception\TimeoutException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Application
{
    private string $id;

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client = null)
    {
        $this->client = $client ?? HttpClient::createForBaseUri('http://broker');
    }

    /**
     * Bootstrap the application by sending a message "Hi, " to broker
     *
     * @return void
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
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

        $this->id = json_decode($response->getContent(), true)['id'];
    }

    /**
     * Request to broker with 1s timout to get response
     *
     * @throws ClientExceptionInterface
     * @throws NoResponseException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function __invoke()
    {
        if (!$this->id) {
            throw new Exception("Application not yet bootstrapped!");
        }

        try {
            $response = $this->client->request('GET', "/requests/{$this->id}", ['timeout' => 1]);

            echo "Response from broker: {$response->getContent()}\n";
        } catch (TimeoutException $e) {
            throw new NoResponseException();
        }
    }
}
