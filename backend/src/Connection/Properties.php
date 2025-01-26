<?php

declare(strict_types=1);

namespace App\Connection;

use App\Color\Color;

readonly class Properties
{
    public function __construct(
        public string $username,
        public Color $color,
    ) {
    }
}
