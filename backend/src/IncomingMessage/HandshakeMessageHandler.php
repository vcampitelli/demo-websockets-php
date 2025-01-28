<?php

declare(strict_types=1);

namespace App\IncomingMessage;

use App\Color\UserColors;
use App\Connection\Properties;
use App\Logger;
use App\OutgoingMessage\ChatHistoryMessage;
use App\OutgoingMessage\UserJoinedMessage;
use App\Repository\ChatHistoryRepository;
use App\Repository\ConnectedClientsRepository;
use App\Repository\PendingClientsRepository;
use Ratchet\ConnectionInterface;

readonly class HandshakeMessageHandler extends Message
{
    private UserColors $colors;

    public function __construct(
        private PendingClientsRepository $pendingClientsRepository,
        private ConnectedClientsRepository $connectedClientsRepository,
        private ChatHistoryRepository $chatHistoryRepository,
        private Logger $logger,
    ) {
        $this->colors = new UserColors();
    }

    public function handle(ConnectionInterface $from, \stdClass $message): void
    {
        if (!$this->pendingClientsRepository->has($from)) {
            return;
        }

        $properties = new Properties(
            username: $message->username,
            color: $this->colors->new(),
        );
        $this->logger->log($from, "Recebida mensagem de autenticação de {$properties->username}", $properties);

        // Avisando os outros usuários que esse usuário entrou
        $message = \json_encode(
            new UserJoinedMessage($from, $properties)
        );
        foreach ($this->connectedClientsRepository as $client) {
            $client->send($message);
        }

        // Adicionando usuário na lista de conectados e enviando as últimas 5 mensagens para ele
        $this->pendingClientsRepository->remove($from);
        $this->connectedClientsRepository->add($from, $properties);
        $from->send(
            \json_encode(new ChatHistoryMessage($this->chatHistoryRepository))
        );
    }
}
