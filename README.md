# Lazy Map

This small library aims at providing a very simple and efficient map of lazy-instantiating objects.

[![Total Downloads](https://poser.pugx.org/ocramius/lazy-map/downloads.png)](https://packagist.org/packages/ocramius/lazy-map)
[![Latest Stable Version](https://poser.pugx.org/ocramius/lazy-map/v/stable.png)](https://packagist.org/packages/ocramius/lazy-map)
[![Latest Unstable Version](https://poser.pugx.org/ocramius/lazy-map/v/unstable.png)](https://packagist.org/packages/ocramius/lazy-map)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2FOcramius%2FLazyMap%2F2.5.x)](https://dashboard.stryker-mutator.io/reports/github.com/Ocramius/LazyMap/2.5.x)

## Installation

The suggested installation method is via [composer](https://getcomposer.org/):

```sh
composer require ocramius/lazy-map
```

## Usage

The current implementation is very simple and allows to define a map of "services" through a
`LazyMap\CallbackLazyMap`:

```php
$map = new \LazyMap\CallbackLazyMap(function ($name) {
    $object = new \stdClass();

    $object->name = $name;

    return $object;
});

var_dump($map->foo);
var_dump($map->bar);
var_dump($map->{'something special'});
```

## Purpose

The idea behind the library is to avoid un-efficient lazy-loading operations like following:

```php
private function getSomething($name)
{
    if (isset($this->initialized[$name]) || array_key_exists($name, $this->initialized)) {
        return $this->initialized[$name];
    }

    return $this->initialized[$name] = new Something($name);
}
```

This reduces overhead greatly when you'd otherwise call `getSomething()` thousands of times.
That's especially useful when mapping a lot of different services and iterating over them
over and over again.

## Performance

LazyMap actually performs much better than the "un-efficient" example that I've shown above.
You can look directly at the performance test suite for details on the tested implementations,
but here are some results for you to have an idea of the boost:

#### Initialized Map Performance:

|Method Name                     |Ops/s          |Relative|
|--------------------------------|---------------|--------|
|initializedArrayPerformance     |2,277,272.90002|100.00% |
|initializedArrayMapPerformance  |1,536,988.76108|148.16% |
|initializedLazyMapPerformance   |4,446,227.23514|51.22%  |


#### Un-Initialized Map Performance:

|Method Name                     |Ops/s          |Relative|
|--------------------------------|---------------|--------|
|unInitializedArrayPerformance : |1,091,720.80627|100.00% |
|unInitializedArrayMapPerformance|688,132.30083  |158.65% |
|unInitializedLazyMapPerformance:|912,191.90744  |119.68% |
