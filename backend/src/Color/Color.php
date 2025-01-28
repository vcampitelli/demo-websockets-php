<?php

declare(strict_types=1);

namespace App\Color;

readonly class Color implements \JsonSerializable
{
    public int $red;
    public int $green;
    public int $blue;

    public function __construct(public string $hex)
    {
        /** @link https://stackoverflow.com/a/15202130 */
        [$this->red, $this->green, $this->blue] = \sscanf($hex, "#%02x%02x%02x");
    }

    public function jsonSerialize(): string
    {
        return $this->hex;
    }
}
