<?php

declare(strict_types=1);

namespace App;

use App\Connection\Properties;
use App\Connection\ResourceId;
use Ratchet\ConnectionInterface;

class Logger
{
    public function log(
        ConnectionInterface $connection,
        string|array $messages,
        ?Properties $properties = null
    ): void {
        $startColor = (isset($properties))
            ? "\e[38;2;{$properties->color->red};{$properties->color->green};{$properties->color->blue}m"
            : '';

        $formattedConnectionId = \sprintf(
            '#%s <%s>',
            new ResourceId($connection),
            ($properties) ? "{$properties->username}" : "\e[3mDesconhecido\e[23m",
        );

        $messages = (\is_array($messages)) ? \array_map('strval', $messages) : [$messages];
        foreach ($messages as $message) {
            echo "{$startColor}[{$formattedConnectionId}] {$message}\e[0m\n";
        }
    }
}
