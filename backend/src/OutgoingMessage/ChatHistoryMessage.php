<?php

declare(strict_types=1);

namespace App\OutgoingMessage;

use App\Repository\ChatHistoryRepository;

readonly class ChatHistoryMessage extends Message
{
    public function __construct(
        private ChatHistoryRepository $chatHistoryRepository,
    ) {
    }

    /**
     * @return array{'messages': array<ChatMessage>}
     */
    public function toArray(): array
    {
        return [
            'messages' => $this->chatHistoryRepository->all(),
        ];
    }

    protected function type(): string
    {
        return 'message_history';
    }
}
