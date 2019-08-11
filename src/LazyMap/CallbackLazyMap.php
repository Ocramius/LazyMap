<?php

declare(strict_types=1);

namespace LazyMap;

/**
 * @psalm-template KeyType of string
 * @psalm-template ValueType
 * @template-extends AbstractLazyMap<KeyName, ValueType>
 */
final class CallbackLazyMap extends AbstractLazyMap
{
    /**
     * @psalm-param callable(KeyType) : ValueType $callback
     */
    public function __construct(callable $callback)
    {
        $this->{self::class . "\0callback"} = $callback;
    }

    /**
     * @psalm-param KeyType $name
     * @psalm-return ValueType
     */
    private function instantiate(string $name)
    {
        /** @psalm-var callable(KeyType) : ValueType $callback */
        $callback = $this->{self::class . "\0callback"};

        return $callback($name);
    }
}
