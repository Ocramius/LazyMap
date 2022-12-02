<?php

declare(strict_types=1);

namespace LazyMap;

use AllowDynamicProperties;

/**
 * @psalm-template T
 * @template-extends AbstractLazyMap<T>
 */
#[AllowDynamicProperties]
final class CallbackLazyMap extends AbstractLazyMap
{
    /** @psalm-param callable(string) : T $callback */
    public function __construct(callable $callback)
    {
        $this->{self::class . "\0callback"} = $callback;
    }

    /**
     * {@inheritDoc}
     */
    protected function instantiate(string $name)
    {
        /** @psalm-var callable(string) : T $callback */
        $callback = $this->{self::class . "\0callback"};

        return $callback($name);
    }
}
