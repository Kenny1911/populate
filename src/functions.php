<?php

declare(strict_types=1);

namespace Kenny1911\Populate;

use Kenny1911\Populate\Exception\RuntimeException;
use ReflectionException;
use ReflectionProperty;

if (!function_exists('is_initialized') && version_compare(phpversion(), '7.4.0', '>=')) {
    function is_initialized($obj, string $prop): bool
    {
        try {
            $prop = new ReflectionProperty($obj, $prop);
        } catch (ReflectionException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */
        return $prop->hasType() ? $prop->isInitialized($obj) : true;
    }
}
