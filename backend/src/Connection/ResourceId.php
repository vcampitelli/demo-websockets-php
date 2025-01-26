<?php

declare(strict_types=1);

namespace App\Connection;

use Ratchet\ConnectionInterface;

class ResourceId implements \Stringable, \JsonSerializable
{
    private string $id;

    public function __construct(ConnectionInterface $conn)
    {
        $this->id = (\property_exists($conn, 'resourceId'))
            ? (string) $conn->resourceId
            : (string) \spl_object_id($conn);
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function jsonSerialize(): string
    {
        return $this->id;
    }
}
