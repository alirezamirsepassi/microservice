<?php

declare(strict_types=1);

namespace Broker\Controller;

use Broker\Repository\RequestRepositoryInterface;
use Exception;
use Interop\Queue\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

final class RequestController
{
    private Context $context;

    private RequestRepositoryInterface $repository;

    public function __construct(RequestRepositoryInterface $repository, Context $context)
    {
        $this->context = $context;
        $this->repository = $repository;
    }

    /**
     * Create request
     *
     * @throws \Exception
     * @throws \Interop\Queue\Exception
     */
    public function create(ServerRequestInterface $request): ResponseInterface
    {
        $data = $this->unserialize($request);
        if (! $this->validate($data)) {
            return $this->json(422, ['error' => 'Validation error has been detected!']);
        }

        $id = $this->repository->create($data['message']);
        $this->dispatch($id, $data['message'], 'Topic_A');

        return $this->json(201, ['id' => $id, 'message' => $data['message']]);
    }

    /**
     * Updates the request
     *
     * @throws \Exception
     * @throws \Interop\Queue\Exception
     */
    public function update(ServerRequestInterface $request, string $id): ResponseInterface
    {
        $data = $this->unserialize($request);
        if (! $this->validate($data)) {
            return $this->json(422, ['error' => 'Validation error has been detected!']);
        }

        $this->repository->update((int) $id, $data['message']);
        $this->dispatch((int) $id, $data['message'], 'Topic_B');

        return $this->json(200, $data);
    }

    /**
     * Returns the request
     *
     * @throws \JsonException
     */
    public function show(ServerRequestInterface $request, string $id): ResponseInterface
    {
        try {
            $data = $this->repository->findOneById((int) $id);

            return $this->json(200, $data);
        } catch (Exception $exception) {
            return $this->json(404, ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Validates the request
     *
     * @param array<string, string> $data
     */
    private function validate(array $data): bool
    {
        return isset($data['message']);
    }

    /**
     * Deserializes the request to array
     *
     * @throws \JsonException
     *
     * @return array<string, string>
     */
    private function unserialize(ServerRequestInterface $request): array
    {
        return json_decode($request->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Dispatch a request to kafka
     *
     * @throws \Interop\Queue\Exception
     * @throws \Interop\Queue\Exception\InvalidDestinationException
     * @throws \Interop\Queue\Exception\InvalidMessageException
     */
    private function dispatch(int $id, string $msg, string $topic): void
    {
        $message = $this->context->createMessage($msg, ['id' => $id]);

        $this->context->createProducer()->send($this->context->createTopic($topic), $message);
    }

    /**
     * Returns json response
     *
     * @throws \JsonException
     *
     * @param array<string, string> $body
     */
    private function json(int $status = 200, array $body = []): ResponseInterface
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($body, JSON_THROW_ON_ERROR));
    }
}
