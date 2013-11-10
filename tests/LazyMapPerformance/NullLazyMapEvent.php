<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace LazyMapPerformance;

use Athletic\AthleticEvent;
use LazyMapTestAsset\NullArrayBasedLazyMap;
use LazyMapTestAsset\NullLazyMap;

/**
 * Performance tests for {@see \LazyMapTestAsset\NullLazyMap}
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class NullLazyMapEvent extends AthleticEvent
{
    /**
     * @todo to be removed: depends on {@link https://github.com/polyfractal/athletic/issues/13}
     *
     * @var \Athletic\Factories\MethodResultsFactory
     */
    private $methodResultsFactory;

    /**
     * @var mixed[]
     */
    private $array;

    /**
     * @var NullArrayBasedLazyMap
     */
    private $arrayMap;

    /**
     * @var NullLazyMap
     */
    private $lazyMap;

    public function setUp()
    {
        $this->array    = array('existingKey' => 0);
        $this->arrayMap = new NullArrayBasedLazyMap();
        $this->lazyMap  = new NullLazyMap();

        // enforcing key initialization
        $this->arrayMap->get('existingKey');
        $this->lazyMap->existingKey;
    }

    /**
     * @baseline
     * @iterations 100000
     * @group initialized-map
     *
     * @return mixed
     */
    public function initializedArrayPerformance()
    {
        if (isset($this->array['existingKey']) || array_key_exists('existingKey', $this->array)) {
            return $this->array['existingKey'];
        }
    }

    /**
     * @iterations 100000
     * @group initialized-map
     *
     * @return mixed
     */
    public function initializedArrayMapPerformance()
    {
        return $this->arrayMap->get('existingKey');
    }

    /**
     * @iterations 100000
     * @group initialized-map
     *
     * @return mixed
     */
    public function initializedLazyMapPerformance()
    {
        return $this->lazyMap->existingKey;
    }

    /**
     * @baseline
     * @iterations 100000
     * @group un-initialized-map
     *
     * @return mixed
     */
    public function unInitializedArrayPerformance()
    {
        if (isset($this->array['nonExistingKey']) || array_key_exists('nonExistingKey', $this->array)) {
            return $this->array['nonExistingKey'];
        }

        return $this->array['nonExistingKey'] = 0;
    }

    /**
     * @iterations 100000
     * @group un-initialized-map
     *
     * @return mixed
     */
    public function unInitializedArrayMapPerformance()
    {
        return $this->arrayMap->get('nonExistingKey');
    }

    /**
     * @iterations 100000
     * @group un-initialized-map
     *
     * @return mixed
     */
    public function unInitializedLazyMapPerformance()
    {
        return $this->lazyMap->nonExistingKey;
    }

    /**
     * @todo to be removed: depends on {@link https://github.com/polyfractal/athletic/issues/13}
     *
     * @param \Athletic\Factories\MethodResultsFactory $methodResultsFactory
     */
    public function setMethodFactory($methodResultsFactory)
    {
        $this->methodResultsFactory = $methodResultsFactory;
    }

    /**
     * @todo to be removed: depends on {@link https://github.com/polyfractal/athletic/issues/13}
     *
     * @return \Athletic\Results\MethodResults[]
     */
    public function run()
    {
        $classReflector   = new \ReflectionClass(get_class($this));

        $methodAnnotations = array();
        foreach ($classReflector->getMethods() as $methodReflector) {
            $methodAnnotations[$methodReflector->getName()] = new \zpt\anno\Annotations($methodReflector);
        }

        $this->classSetUp();
        $results = $this->runBenchmarks($methodAnnotations);
        $this->classTearDown();

        return $results;
    }

    /**
     * @todo to be removed: depends on {@link https://github.com/polyfractal/athletic/issues/13}
     *
     * @param \zpt\anno\Annotations[] $methods
     *
     * @return \Athletic\Results\MethodResults[]
     */
    private function runBenchmarks($methods)
    {
        $results = array();

        foreach ($methods as $methodName => $annotations) {
            if (isset($annotations['iterations']) === true) {
                $results[] = $this->runMethodBenchmark($methodName, $annotations);
                $this->tearDown();
            }
        }
        return $results;
    }

    /**
     * @todo to be removed: depends on {@link https://github.com/polyfractal/athletic/issues/13}
     *
     * @param string $method
     * @param int    $annotations
     *
     * @return \Athletic\Results\MethodResults
     */
    private function runMethodBenchmark($method, $annotations)
    {
        $iterations = $annotations['iterations'];
        $avgCalibration = $this->getCalibrationTime($iterations);

        $results = array();
        for ($i = 0; $i < $iterations; ++$i) {
            $this->setUp();
            $results[$i] = $this->timeMethod($method) - $avgCalibration;
        }

        $finalResults = $this->methodResultsFactory->create($method, $results, $iterations);

        $this->setOptionalAnnotations($finalResults, $annotations);

        return $finalResults;

    }

    /**
     * @todo to be removed: depends on {@link https://github.com/polyfractal/athletic/issues/13}
     *
     * @param string $method
     *
     * @return mixed
     */
    private function timeMethod($method)
    {
        $start = microtime(true);
        $this->$method();
        return microtime(true) - $start;
    }

    /**
     * @todo to be removed: depends on {@link https://github.com/polyfractal/athletic/issues/13}
     *
     * @param int $iterations
     *
     * @return float
     */
    private function getCalibrationTime($iterations)
    {
        $emptyCalibrationMethod = 'emptyCalibrationMethod';
        $resultsCalibration     = array();
        for ($i = 0; $i < $iterations; ++$i) {
            $resultsCalibration[$i] = $this->timeMethod($emptyCalibrationMethod);
        }
        return array_sum($resultsCalibration) / count($resultsCalibration);
    }

    /**
     * @todo to be removed: depends on {@link https://github.com/polyfractal/athletic/issues/13}
     */
    private function emptyCalibrationMethod()
    {

    }

    /**
     * @todo to be removed: depends on {@link https://github.com/polyfractal/athletic/issues/13}
     *
     * @param \Athletic\Results\MethodResults $finalResults
     * @param array         $annotations
     */
    private function setOptionalAnnotations(\Athletic\Results\MethodResults $finalResults, $annotations)
    {
        if (isset($annotations['group']) === true) {
            $finalResults->setGroup($annotations['group']);
        }

        if (isset($annotations['baseline']) === true) {
            $finalResults->setBaseline();
        }
    }
}
