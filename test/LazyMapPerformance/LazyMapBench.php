<?php

declare(strict_types=1);

namespace LazyMapPerformance;

use LazyMapTestAsset\NullArrayBasedLazyMap;
use LazyMapTestAsset\NullLazyMap;
use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use function array_key_exists;

/**
 * @BeforeMethods({"setUp"})
 */
class LazyMapBench
{
    /** @var array<string, int> */
    private array $array;

    private NullArrayBasedLazyMap $arrayMap;

    private NullLazyMap $lazyMap;

    public function setUp() : void
    {
        $this->array    = ['existingKey' => 0];
        $this->arrayMap = new NullArrayBasedLazyMap();
        $this->lazyMap  = new NullLazyMap();

        // enforcing key initialization
        $this->arrayMap->get('existingKey');
        $this->lazyMap->existingKey;
    }

    public function benchInitializedArrayPerformance() : int
    {
        if (array_key_exists('existingKey', $this->array)) {
            return $this->array['existingKey'];
        }

        return 0;
    }

    public function benchInitializedArrayMapPerformance() : int
    {
        return $this->arrayMap->get('existingKey');
    }

    /** @return null */
    public function benchInitializedLazyMapPerformance()
    {
        return $this->lazyMap->existingKey;
    }

    public function benchUnInitializedArrayPerformance() : int
    {
        if (array_key_exists('nonExistingKey', $this->array)) {
            return $this->array['nonExistingKey'];
        }

        return $this->array['nonExistingKey'] = 0;
    }

    public function benchUnInitializedArrayMapPerformance() : int
    {
        return $this->arrayMap->get('nonExistingKey');
    }

    /** @return null */
    public function benchUnInitializedLazyMapPerformance()
    {
        return $this->lazyMap->nonExistingKey;
    }
}
