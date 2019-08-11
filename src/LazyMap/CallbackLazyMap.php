<?php

declare(strict_types=1);

namespace LazyMap;

/**
 * @psalm-template T
 * @template-extends AbstractLazyMap<T>
 */
class CallbackLazyMap extends AbstractLazyMap
{
    /**
     * @psalm-param callable(string) : T
     */
    public function __construct(callable $callback)
    {
        $this->{__CLASS__ . "\0callback"} = $callback;
    }

    /**
     * {@inheritDoc}
     */
    protected function instantiate($name)
    {
        /** @psalm-var callable(string) : T $callback */
        $callback = $this->{__CLASS__ . "\0callback"};

        return $callback($name);
    }
}
