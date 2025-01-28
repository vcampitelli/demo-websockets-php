<?php

declare(strict_types=1);

namespace App\IncomingMessage;

use App\Logger;
use App\Repository\ChatHistoryRepository;
use App\Repository\ConnectedClientsRepository;
use App\Repository\PendingClientsRepository;
use DomainException;
use Ratchet\ConnectionInterface;

readonly class MessageHandler
{
    private HandshakeMessageHandler $handshakeMessageHandler;

    private ChatMessageHandler $chatMessageHandler;

    public function __construct(
        PendingClientsRepository $pendingClientsRepository,
        ConnectedClientsRepository $connectedClientsRepository,
        ChatHistoryRepository $chatHistoryRepository,
        Logger $logger,
    ) {
        $this->handshakeMessageHandler = new HandshakeMessageHandler(
            pendingClientsRepository: $pendingClientsRepository,
            connectedClientsRepository: $connectedClientsRepository,
            chatHistoryRepository: $chatHistoryRepository,
            logger: $logger,
        );
        $this->chatMessageHandler = new ChatMessageHandler(
            connectedClientsRepository: $connectedClientsRepository,
            chatHistoryRepository: $chatHistoryRepository,
            logger: $logger,
        );
    }

    public function handle(ConnectionInterface $connection, string $message): void
    {
        $json = \json_decode($message);
        if (!\is_object($json)) {
            throw new DomainException();
        }

        match ($json->type ?? null) {
            'join' => $this->handshakeMessageHandler->handle($connection, $json),
            'message' => $this->chatMessageHandler->handle($connection, $json),
            default => throw new DomainException(),
        };
    }
}
