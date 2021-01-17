<?php

declare(strict_types=1);

namespace LazyMapTestAsset;

use function array_key_exists;

/**
 * Simple classical array-based map - used to simulate the overhead of a classical array-based solution
 */
class NullArrayBasedLazyMap
{
    /** @var array<string, int> */
    private array $items = [];

    /** Lazy getter - retrieves or instantiates a key in the map */
    public function & get(string $name): int
    {
        if (isset($this->items[$name]) || array_key_exists($name, $this->items)) {
            return $this->items[$name];
        }

        $this->items[$name] = $this->instantiate($name);

        return $this->items[$name];
    }

    /**
     * Null instantiator, emulates same overhead of an {@see \LazyMapTestAsset\NullLazyMap}
     */
    protected function instantiate(string $name): int
    {
        return 0;
    }
}
