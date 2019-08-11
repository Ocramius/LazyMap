<?php

declare(strict_types=1);

namespace LazyMapTestAsset;

final class InstantiableClassB
{
    public function getClassName() : string
    {
        return self::class;
    }
}
