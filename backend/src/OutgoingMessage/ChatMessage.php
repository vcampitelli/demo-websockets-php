<?php

declare(strict_types=1);

namespace App\OutgoingMessage;

use App\Connection\Properties;
use App\Connection\ResourceId;
use Ratchet\ConnectionInterface;

readonly class ChatMessage extends Message
{
    public function __construct(
        private ConnectionInterface $from,
        private Properties $properties,
        private int $messageId,
        private string $message,
    ) {
    }

    /**
     * @return array{'id': int, 'from': array{'id': ResourceId, 'username': string, 'color': \App\Color\Color}}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->messageId,
            'from' => [
                'id' => new ResourceId($this->from),
                'username' => $this->properties->username,
                'color' => $this->properties->color,
            ],
            'message' => $this->message,
        ];
    }

    protected function type(): string
    {
        return 'message';
    }
}
