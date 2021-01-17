<?php

declare(strict_types=1);

namespace LazyMap;

/**
 * @psalm-template KeyType of string
 * @psalm-template ValueType
 * @template-extends AbstractLazyMap<KeyType, ValueType>
 */
final class CallbackLazyMap extends AbstractLazyMap
{
    /**
     * This is a trade-off: the callback lazy map was designed to
     * have no accessible properties for its internal details, but
     * somebody requesting explicitly for the key "internalCallbackDoNotReferenceThisThereWillBeDragons"
     * is indeed looking for trouble.
     *
     * The initial design used a key with a "\0" in its name, but
     * that was too hacky and led to optimizations and type inference
     * problems that are not worth the added safety.
     *
     * @var callable
     *
     * @psalm-var callable(KeyType) : ValueType
     */
    private $internalCallbackDoNotReferenceThisThereWillBeDragons;

    /**
     * @psalm-param callable(KeyType) : ValueType $callback
     */
    public function __construct(callable $callback)
    {
        $this->internalCallbackDoNotReferenceThisThereWillBeDragons = $callback;
    }

    /**
     * @psalm-param KeyType $name
     * @psalm-return ValueType
     */
    protected function instantiate(string $name)
    {
        return ($this->internalCallbackDoNotReferenceThisThereWillBeDragons)($name);
    }
}
