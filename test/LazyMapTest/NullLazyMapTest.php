<?php

declare(strict_types=1);

namespace LazyMapTest;

use LazyMap\AbstractLazyMap;
use LazyMapTestAsset\NullLazyMap;
use PHPUnit\Framework\TestCase;

/** @covers \LazyMap\AbstractLazyMap */
class NullLazyMapTest extends TestCase
{
    protected AbstractLazyMap $lazyMap;

    public function setUp(): void
    {
        $this->lazyMap = new NullLazyMap();
    }

    public function testDirectPropertyAccess(): void
    {
        self::assertSame(null, $this->lazyMap->foo);
        self::assertSame(null, $this->lazyMap->bar);
        self::assertSame(null, $this->lazyMap->{'baz\\tab'});
    }
}
