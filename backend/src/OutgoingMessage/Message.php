<?php

declare(strict_types=1);

namespace App\OutgoingMessage;

use JsonSerializable;

abstract readonly class Message implements JsonSerializable
{
    final public function jsonSerialize(): array
    {
        $data = $this->toArray();
        $data['type'] = $this->type();
        $data['timestamp'] = \time();
        return $data;
    }

    abstract protected function toArray(): array;

    abstract protected function type(): string;
}
