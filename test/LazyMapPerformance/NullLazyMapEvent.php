<?php

declare(strict_types=1);

namespace LazyMapPerformance;

use Athletic\AthleticEvent;
use LazyMapTestAsset\NullArrayBasedLazyMap;
use LazyMapTestAsset\NullLazyMap;
use function array_key_exists;

/** Performance tests for {@see \LazyMapTestAsset\NullLazyMap} */
class NullLazyMapEvent extends AthleticEvent
{
    /** @var mixed[] */
    private $array;

    /** @var NullArrayBasedLazyMap */
    private $arrayMap;

    /** @var NullLazyMap */
    private $lazyMap;

    public function setUp() : void
    {
        $this->array    = ['existingKey' => 0];
        $this->arrayMap = new NullArrayBasedLazyMap();
        $this->lazyMap  = new NullLazyMap();

        // enforcing key initialization
        $this->arrayMap->get('existingKey');
        $this->lazyMap->existingKey;
    }

    /**
     * @return mixed
     *
     * @baseline
     * @iterations 100000
     * @group initialized-map
     */
    public function initializedArrayPerformance()
    {
        if (isset($this->array['existingKey']) || array_key_exists('existingKey', $this->array)) {
            return $this->array['existingKey'];
        }
    }

    /**
     * @return mixed
     *
     * @iterations 100000
     * @group initialized-map
     */
    public function initializedArrayMapPerformance()
    {
        return $this->arrayMap->get('existingKey');
    }

    /**
     * @return mixed
     *
     * @iterations 100000
     * @group initialized-map
     */
    public function initializedLazyMapPerformance()
    {
        return $this->lazyMap->existingKey;
    }

    /**
     * @return mixed
     *
     * @baseline
     * @iterations 100000
     * @group un-initialized-map
     */
    public function unInitializedArrayPerformance()
    {
        if (isset($this->array['nonExistingKey']) || array_key_exists('nonExistingKey', $this->array)) {
            return $this->array['nonExistingKey'];
        }

        return $this->array['nonExistingKey'] = 0;
    }

    /**
     * @return mixed
     *
     * @iterations 100000
     * @group un-initialized-map
     */
    public function unInitializedArrayMapPerformance()
    {
        return $this->arrayMap->get('nonExistingKey');
    }

    /**
     * @return mixed
     *
     * @iterations 100000
     * @group un-initialized-map
     */
    public function unInitializedLazyMapPerformance()
    {
        return $this->lazyMap->nonExistingKey;
    }
}
