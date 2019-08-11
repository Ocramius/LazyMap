<?php

declare(strict_types=1);

namespace LazyMap;

/**
 * @psalm-template KeyType of string
 * @psalm-template ValueType
 */
abstract class AbstractLazyMap
{
    /**
     * Magic PHP getter {@link http://www.php.net/manual/en/language.oop5.overloading.php#object.get}
     *
     * @return mixed reference to the instantiated property
     *
     * @psalm-param KeyType $name
     * @psalm-return ValueType
     * @psalm-suppress MixedInferredReturnType
     */
    public function & __get(string $name)
    {
        $this->$name = $this->instantiate($name);

        /** @psalm-suppress MixedReturnStatement */
        return $this->$name;
    }

    /**
     * Instantiate a particular key by the given name
     *
     * @return mixed
     *
     * @psalm-param KeyType $name
     * @psalm-return ValueType
     */
    abstract protected function instantiate(string $name);
}
