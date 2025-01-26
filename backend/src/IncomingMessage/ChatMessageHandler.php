<?php

declare(strict_types=1);

namespace App\IncomingMessage;

use App\Logger;
use App\OutgoingMessage\ChatMessage;
use Ratchet\ConnectionInterface;

class ChatMessageHandler
{
    /**
     * Histórico de mensagens
     * @var array<ChatMessage>
     */
    private array $messages = [];

    /**
     * Contador para o ID da mensagem
     * @var int
     */
    private int $messageId = 0;

    public function __construct(
        private readonly \SplObjectStorage $connectedClients,
        private readonly Logger $logger,
    ) {
    }

    public function handle(ConnectionInterface $from, \stdClass $json): void
    {
        if (!$this->connectedClients->contains($from)) {
            return;
        }

        $properties = $this->connectedClients->offsetGet($from);
        $this->logger->log($from, 'Recebida mensagem do chat', $properties);

        $message = new ChatMessage(
            from: $from,
            properties: $properties,
            messageId: $this->messageId++,
            message: $json->message,
        );
        $jsonMessage = \json_encode($message);
        $this->messages[] = $message;
        foreach ($this->connectedClients as $client) {
            $client->send($jsonMessage);
        }
    }
}
