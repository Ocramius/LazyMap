<?php

declare(strict_types=1);

namespace LazyMapTest;

use LazyMap\AbstractLazyMap;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

/** @covers \LazyMap\AbstractLazyMap */
class AbstractLazyMapTest extends TestCase
{
    /** @var AbstractLazyMap&MockObject */
    protected AbstractLazyMap $lazyMap;

    public function setUp(): void
    {
        $this->lazyMap = $this->getMockForAbstractClass(AbstractLazyMap::class);
    }

    public function testDirectPropertyAccess(): void
    {
        /** @psalm-var AbstractLazyMap<string>&MockObject $lazyMap */
        $lazyMap = $this->lazyMap;

        $lazyMap
            ->expects($this->exactly(3))
            ->method('instantiate')
            ->with($this->isType('string'))
            ->willReturnCallback(static function (string $key): string {
                return $key . ' - initialized value';
            });

        self::assertSame('foo - initialized value', $lazyMap->foo);
        self::assertSame('bar - initialized value', $lazyMap->bar);
        self::assertSame('baz\\tab - initialized value', $lazyMap->{'baz\\tab'});
    }

    public function testMultipleDirectPropertyAccessDoesNotTriggerSameInstantiation(): void
    {
        /** @psalm-var AbstractLazyMap<stdClass>&MockObject $lazyMap */
        $lazyMap = $this->lazyMap;

        $lazyMap
            ->expects($this->exactly(2))
            ->method('instantiate')
            ->with($this->isType('string'))
            ->willReturnCallback(static function (string $key): stdClass {
                return new stdClass();
            });

        $foo = $lazyMap->foo;

        self::assertSame($foo, $lazyMap->foo);

        $bar = $lazyMap->bar;

        self::assertSame($bar, $lazyMap->bar);

        self::assertNotSame($bar, $foo);
    }

    public function testUnSettingPropertiesRemovesSharedInstance(): void
    {
        /** @psalm-var AbstractLazyMap<stdClass>&MockObject $lazyMap */
        $lazyMap = $this->lazyMap;

        $lazyMap
            ->expects($this->exactly(2))
            ->method('instantiate')
            ->with($this->isType('string'))
            ->willReturnCallback(static function (string $key): stdClass {
                return new stdClass();
            });

        $foo = $lazyMap->foo;

        self::assertSame($foo, $lazyMap->foo);

        unset($lazyMap->foo);

        $bar = $lazyMap->foo;

        self::assertSame($bar, $lazyMap->foo);
        self::assertNotSame($bar, $foo);
    }
}
