<?php

declare(strict_types=1);

namespace App\Repository;

use Ratchet\ConnectionInterface;
use SplObjectStorage;

class PendingClientsRepository
{
    /**
     * @var SplObjectStorage<ConnectionInterface, null>
     */
    private SplObjectStorage $storage;

    public function __construct()
    {
        $this->storage = new SplObjectStorage();
    }

    public function add(ConnectionInterface $connection): void
    {
        $this->storage->attach($connection);
    }

    public function remove(ConnectionInterface $connection): void
    {
        $this->storage->detach($connection);
    }

    public function has(ConnectionInterface $connection): bool
    {
        return $this->storage->contains($connection);
    }
}
