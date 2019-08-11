<?php

declare(strict_types=1);

namespace LazyMapTestAsset;

final class InstantiableClassA
{
    public function getClassName() : string
    {
        return self::class;
    }
}
