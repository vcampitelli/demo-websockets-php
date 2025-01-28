<?php

use App\Chat;
use App\Logger;
use App\Repository\ChatHistoryRepository;
use App\Repository\ConnectedClientsRepository;
use App\Repository\PendingClientsRepository;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/../vendor/autoload.php';

$port = $_ENV['PORT'] ?? 8000;
echo "Iniciando backend do WebSocket na porta {$port}...\n";

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat(
                pendingClientsRepository: new PendingClientsRepository(),
                connectedClientsRepository: new ConnectedClientsRepository(),
                chatHistoryRepository: new ChatHistoryRepository(5),
                logger: new Logger(),
            ),
        ),
    ),
    $port,
);

$server->run();
