<?php

declare(strict_types=1);

namespace App;

use App\IncomingMessage\MessageHandler;
use App\OutgoingMessage\UserLeftMessage;
use App\Repository\ChatHistoryRepository;
use App\Repository\ConnectedClientsRepository;
use App\Repository\PendingClientsRepository;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Chat implements MessageComponentInterface
{
    /**
     * Handler de mensagens
     * @var MessageHandler
     */
    private MessageHandler $incomingMessageHandler;

    /**
     * @param PendingClientsRepository $pendingClientsRepository Guarda clientes que ainda não se identificaram
     * @param ConnectedClientsRepository $connectedClientsRepository Guarda clientes identificados
     * @param ChatHistoryRepository $chatHistoryRepository Guarda o histórico de mensagens enviadas
     * @param Logger $logger Objeto para fazer o logging
     */
    public function __construct(
        private readonly PendingClientsRepository $pendingClientsRepository,
        private readonly ConnectedClientsRepository $connectedClientsRepository,
        ChatHistoryRepository $chatHistoryRepository,
        private readonly Logger $logger,
    ) {
        $this->incomingMessageHandler = new MessageHandler(
            pendingClientsRepository: $pendingClientsRepository,
            connectedClientsRepository: $connectedClientsRepository,
            chatHistoryRepository: $chatHistoryRepository,
            logger: $this->logger,
        );
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->pendingClientsRepository->add($conn);
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
        $this->pendingClientsRepository->remove($conn);
        if (!$this->connectedClientsRepository->has($conn)) {
            return;
        }

        $properties = $this->connectedClientsRepository->get($conn);
        $this->logger->log($conn, 'Saindo do chat', $properties);
        $this->connectedClientsRepository->remove($conn);

        $jsonMessage = \json_encode(
            new UserLeftMessage($conn, $properties)
        );
        foreach ($this->connectedClientsRepository as $client) {
            $client->send($jsonMessage);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        $properties = ($this->connectedClientsRepository->has($conn))
            ? $this->connectedClientsRepository->get($conn)
            : null;

        $this->pendingClientsRepository->remove($conn);
        $this->connectedClientsRepository->remove($conn);

        $this->logger->log($conn, "Erro na conexão: {$e->getMessage()}", $properties);

        $conn->close();
    }
}
