<?php

declare(strict_types=1);

namespace App\IncomingMessage;

use App\Color\UserColors;
use App\Connection\Properties;
use App\Logger;
use App\OutgoingMessage\ChatHistoryMessage;
use App\OutgoingMessage\UserJoinedMessage;
use Ratchet\ConnectionInterface;

readonly class HandshakeMessageHandler extends Message
{
    private UserColors $colors;

    public function __construct(
        private \SplObjectStorage $pendingAuthenticationClients,
        private \SplObjectStorage $connectedClients,
        private Logger $logger,
    ) {
        $this->colors = new UserColors();
    }

    public function handle(ConnectionInterface $from, \stdClass $message): void
    {
        if (!$this->pendingAuthenticationClients->contains($from)) {
            return;
        }

        $properties = new Properties(
            username: $message->username,
            color: $this->colors->new(),
        );
        $this->logger->log($from, "Recebida mensagem de autenticação de {$properties->username}", $properties);

        // Removendo usuário da lista de pendentes e avisando os outros usuários
        $message = \json_encode([
            new UserJoinedMessage($from, $properties)
        ]);
        foreach ($this->connectedClients as $client) {
            $client->send($message);
        }

        // Adicionando usuário na lista de conectados e enviando as últimas 5 mensagens par aele
        $this->pendingAuthenticationClients->detach($from);
        $this->connectedClients->attach($from, $properties);
        $from->send(
            \json_encode(new ChatHistoryMessage($this->messages))
        );
    }
}
