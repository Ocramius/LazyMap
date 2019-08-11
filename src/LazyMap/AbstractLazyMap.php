<?php

declare(strict_types=1);

namespace LazyMap;

/**
 * @psalm-template T
 */
abstract class AbstractLazyMap
{
    /**
     * Magic PHP getter {@link http://www.php.net/manual/en/language.oop5.overloading.php#object.get}
     *
     * @param string $name
     *
     * @return mixed reference
     *
     * @psalm-return T
     * @psalm-suppress MixedInferredReturnType
     */
    public function & __get(string $name)
    {
        $this->$name = $this->instantiate($name);

        // assignment and return is not possible since PHP will segfault (bug report will come)
        /** @psalm-suppress MixedReturnStatement */
        return $this->$name;
    }

    /**
     * Instantiate a particular key by the given name
     *
     * @param string $name
     *
     * @return mixed
     *
     * @psalm-return T
     */
    abstract protected function instantiate(string $name);
}
