<?php

declare(strict_types=1);

namespace App\OutgoingMessage;

readonly class ChatHistoryMessage extends Message
{
    /**
     * @param array<ChatMessage> $messages
     */
    public function __construct(
        private array $messages
    ) {
        // @TODO check for ChatMessage
    }

    public function toArray(): array
    {
        return [
            'messages' => $this->messages,
        ];
    }

    protected function type(): string
    {
        return 'message_history';
    }
}
