<?php

declare(strict_types=1);

namespace App\Repository;

use App\Connection\Properties;
use IteratorAggregate;
use Ratchet\ConnectionInterface;
use SplObjectStorage;
use Traversable;

/**
 * @implements IteratorAggregate<int, ConnectionInterface>
 */
class ConnectedClientsRepository implements IteratorAggregate
{
    /**
     * @var SplObjectStorage<ConnectionInterface, Properties>
     */
    private SplObjectStorage $storage;

    public function __construct()
    {
        $this->storage = new SplObjectStorage();
    }

    public function add(ConnectionInterface $connection, Properties $properties): void
    {
        $this->storage->attach($connection, $properties);
    }

    public function get(ConnectionInterface $connection): Properties
    {
        return $this->storage->offsetGet($connection);
    }

    public function remove(ConnectionInterface $connection): void
    {
        $this->storage->detach($connection);
    }

    public function has(ConnectionInterface $connection): bool
    {
        return $this->storage->contains($connection);
    }

    public function getIterator(): Traversable
    {
        return $this->storage;
    }
}
