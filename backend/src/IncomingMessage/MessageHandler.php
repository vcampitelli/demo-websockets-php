<?php

declare(strict_types=1);

namespace App\IncomingMessage;

use App\Logger;
use App\OutgoingMessage\ChatHistoryMessage;
use App\OutgoingMessage\UserJoinedMessage;
use DomainException;
use Ratchet\ConnectionInterface;

readonly class MessageHandler
{
    private HandshakeMessageHandler $handshakeMessageHandler;

    private ChatMessageHandler $chatMessageHandler;

    public function __construct(
        \SplObjectStorage $pendingAuthenticationClients,
        \SplObjectStorage $connectedClients,
        Logger $logger,
    ) {
        $this->handshakeMessageHandler = new HandshakeMessageHandler(
            pendingAuthenticationClients: $pendingAuthenticationClients,
            connectedClients: $connectedClients,
            logger: $logger,
        );
        $this->chatMessageHandler = new ChatMessageHandler(
            connectedClients: $connectedClients,
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
