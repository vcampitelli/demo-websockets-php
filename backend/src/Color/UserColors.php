<?php

declare(strict_types=1);

namespace App\Color;

class UserColors
{
    /**
     * @link https://picocss.com/docs/colors
     * @var array<Color>
     */
    private array $colors = [];

    public function __construct()
    {
        $colors = [
            '#c52f21', // red
            '#d92662', // pink
            '#c1208b', // fuchsia
            '#9236a4', // purple
            '#7540bf', // violet
            '#524ed2', // indigo
            '#2060df', // blue
            '#0172ad', // azure
            '#047878', // cyan
            '#007a50', // jade
            '#398712', // green
            '#a5d601', // lime
            '#f2df0d', // yellow
            '#ffbf00', // amber
            '#ff9500', // pumpkin
            '#d24317', // orange
            '#ccc6b4', // sand
            '#ababab', // grey
            '#646b79', // zinc
            '#525f7a', // slate
            '#ffffff', // light
            '#000000', // dark
        ];
        foreach ($colors as $hex) {
            $this->colors[$hex] = new Color($hex);
        }
    }

    public function new(): Color
    {
        $color = \current($this->colors);

        // Pegamos a próxima cor
        if ($color !== null) {
            \next($this->colors);
            return $color;
        }

        // Começamos da primeira cor
        \reset($this->colors);
        return \current($this->colors);
    }

    public function fromHex(string $hex): ?Color
    {
        return $this->colors[$hex] ?? null;
    }
}
