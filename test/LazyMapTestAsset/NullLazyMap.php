<?php

declare(strict_types=1);

namespace LazyMapTestAsset;

use LazyMap\AbstractLazyMap;

/**
 * Example lazy map producing only null values
 *
 * @template-extends AbstractLazyMap<null>
 */
class NullLazyMap extends AbstractLazyMap
{
    /** @return null */
    protected function instantiate(string $name)
    {
        return null;
    }
}
