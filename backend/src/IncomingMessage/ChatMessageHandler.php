<?php

declare(strict_types=1);

namespace App\IncomingMessage;

use App\Logger;
use App\OutgoingMessage\ChatMessage;
use App\Repository\ChatHistoryRepository;
use App\Repository\ConnectedClientsRepository;
use Ratchet\ConnectionInterface;

class ChatMessageHandler
{
    /**
     * Contador para o ID da mensagem
     * @var int
     */
    private int $messageId = 0;

    public function __construct(
        private readonly ConnectedClientsRepository $connectedClientsRepository,
        private readonly ChatHistoryRepository $chatHistoryRepository,
        private readonly Logger $logger,
    ) {
    }

    public function handle(ConnectionInterface $from, \stdClass $json): void
    {
        if (!$this->connectedClientsRepository->has($from)) {
            return;
        }

        $properties = $this->connectedClientsRepository->get($from);
        $this->logger->log($from, 'Recebida mensagem do chat', $properties);

        $message = new ChatMessage(
            from: $from,
            properties: $properties,
            messageId: $this->messageId++,
            message: $json->message,
        );
        $jsonMessage = \json_encode($message);
        $this->chatHistoryRepository->add($message);
        foreach ($this->connectedClientsRepository as $client) {
            $client->send($jsonMessage);
        }
    }
}
