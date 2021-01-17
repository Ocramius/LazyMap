<?php

declare(strict_types=1);

namespace LazyMapTestAsset;

/** Callable class - useful for stubbing __invoke */
abstract class CallableClass
{
    abstract public function __invoke(string $name): string;
}
