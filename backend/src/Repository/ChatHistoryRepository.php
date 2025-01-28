<?php

declare(strict_types=1);

namespace App\Repository;

use App\OutgoingMessage\ChatMessage;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<int, ChatMessage>
 */
class ChatHistoryRepository implements IteratorAggregate
{
    /**
     * @var array<ChatMessage>
     */
    private array $storage = [];

    private int $count = 0;

    public function __construct(private readonly int $limit)
    {
    }

    public function add(ChatMessage $message): self
    {
        $this->storage[] = $message;

        if ($this->count === $this->limit) {
            \array_shift($this->storage);
            return $this;
        }

        $this->count++;
        return $this;
    }

    /**
     * @return ChatMessage[]
     */
    public function all(): array
    {
        return $this->storage;
    }

    /**
     * @return Traversable<int, ChatMessage>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->storage);
    }
}
