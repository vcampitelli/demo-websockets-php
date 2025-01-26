<?php

declare(strict_types=1);

namespace App\OutgoingMessage;

use App\Connection\Properties;
use App\Connection\ResourceId;
use Ratchet\ConnectionInterface;

readonly class UserLeftMessage extends Message
{
    public function __construct(
        private ConnectionInterface $conn,
        private Properties $properties
    ) {
    }

    public function toArray(): array
    {
        return [
            'from' => [
                'id' => new ResourceId($this->conn),
                'username' => $this->properties->username,
                'color' => $this->properties->color,
            ],
        ];
    }

    protected function type(): string
    {
        return 'user_left';
    }
}
