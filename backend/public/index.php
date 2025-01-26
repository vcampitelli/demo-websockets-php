<?php

use App\Chat;
use App\Logger;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/../vendor/autoload.php';

$pendingAuthenticationClients = new SplObjectStorage();
$connectedClients = new SplObjectStorage();

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat(
                pendingAuthenticationClients: $pendingAuthenticationClients,
                connectedClients: $connectedClients,
                logger: new Logger(),
            ),
        ),
    ),
    $_ENV['PORT'] ?? 8000,
);

$server->run();
