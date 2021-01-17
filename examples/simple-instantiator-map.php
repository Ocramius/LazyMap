<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use LazyMap\CallbackLazyMap;

$map = new CallbackLazyMap(static function (string $name): stdClass {
    $object = new stdClass();

    $object->name = $name;

    return $object;
});

// the map instantiates objects lazily
var_dump($map->foo);
var_dump($map->bar);
var_dump($map->baz);

// same properties return the same object (shared instance)
var_dump($map->foo === $map->foo);

// different properties contain different instances
var_dump($map->foo === $map->bar);
var_dump($map->bar === $map->baz);
var_dump($map->baz === $map->foo);
