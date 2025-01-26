<?php

declare(strict_types=1);

namespace App;

use App\Connection\Properties;
use App\Connection\ResourceId;
use App\IncomingMessage\MessageHandler;
use App\OutgoingMessage\ChatMessage;
use App\OutgoingMessage\UserLeftMessage;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class Chat implements MessageComponentInterface
{
    /**
     * Handler de mensagens
     * @var MessageHandler
     */
    private MessageHandler $incomingMessageHandler;

    /**
     * @param SplObjectStorage<ConnectionInterface, null> $pendingAuthenticationClients Guarda os clientes que ainda não
     *                                                                                  se identificaram
     * @param SplObjectStorage<ConnectionInterface, Properties> $connectedClients Guarda os clientes identificados
     */
    public function __construct(
        private readonly SplObjectStorage $pendingAuthenticationClients,
        private readonly SplObjectStorage $connectedClients,
        private readonly Logger $logger,
    ) {
        $this->incomingMessageHandler = new MessageHandler(
            pendingAuthenticationClients: $this->pendingAuthenticationClients,
            connectedClients: $this->connectedClients,
            logger: $this->logger,
        );
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->pendingAuthenticationClients->attach($conn);
        $this->logger->log($conn, 'Nova conexão criada');
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        try {
            $this->incomingMessageHandler->handle($from, $msg);
        } catch (\DomainException) {
            $this->logger->log($from, "Mensagem desconhecida: {$msg}");
        } catch (\Throwable $t) {
            $this->logger->log($from, [
                'Erro ao processar mensagem recebida',
                "  Mensagem: {$msg}",
                "  Erro: {$t->getMessage()}",
            ]);
        }
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->pendingAuthenticationClients->detach($conn);
        if (!$this->connectedClients->contains($conn)) {
            return;
        }

        $properties = $this->connectedClients->offsetGet($conn);
        $this->logger->log($conn, 'Saindo do chat', $properties);
        $this->connectedClients->detach($conn);

        $jsonMessage = \json_encode(
            new UserLeftMessage($conn, $properties)
        );
        foreach ($this->connectedClients as $client) {
            $client->send($jsonMessage);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        $properties = ($this->connectedClients->contains($conn))
            ? $this->connectedClients->offsetGet($conn)
            : null;

        $this->pendingAuthenticationClients->detach($conn);
        $this->connectedClients->detach($conn);

        $this->logger->log($conn, "Erro na conexão: {$e->getMessage()}", $properties);

        $conn->close();
    }
}
