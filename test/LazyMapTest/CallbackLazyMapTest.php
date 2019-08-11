<?php

declare(strict_types=1);

namespace LazyMapTest;

use LazyMap\CallbackLazyMap;
use LazyMapTestAsset\CallableClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/** @covers \LazyMap\CallbackLazyMap */
class CallbackLazyMapTest extends TestCase
{
    /** @var CallbackLazyMap */
    protected $lazyMap;

    /** @var CallableClass&MockObject */
    protected $callback;

    /**
     * {@inheritDoc}
     */
    public function setUp() : void
    {
        $this->callback = $this->createMock(CallableClass::class);
        $this->lazyMap  = new CallbackLazyMap(function (string $name) : string {
            return $this->callback->__invoke($name);
        });
    }

    public function testDirectPropertyAccess() : void
    {
        $count = 0;
        $this
            ->callback
            ->expects($this->exactly(3))
            ->method('__invoke')
            ->willReturnCallback(static function (string $name) use (& $count) : string {
                self::assertIsInt($count);

                $count += 1;

                return $name . ' - ' . $count;
            });

        self::assertSame('foo - 1', $this->lazyMap->foo);
        self::assertSame('bar - 2', $this->lazyMap->bar);
        self::assertSame('baz\\tab - 3', $this->lazyMap->{'baz\\tab'});
    }
}
