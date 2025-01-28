<?php

declare(strict_types=1);

namespace App\OutgoingMessage;

use JsonSerializable;

abstract readonly class Message implements JsonSerializable
{
    /**
     * @return array{'type': string, 'timestamp': int}&array<mixed>
     */
    final public function jsonSerialize(): array
    {
        $data = $this->toArray();
        $data['type'] = $this->type();
        $data['timestamp'] = \time();
        return $data;
    }

    /**
     * @return array<mixed>
     */
    abstract protected function toArray(): array;

    abstract protected function type(): string;
}
