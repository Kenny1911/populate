<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor;

use Kenny1911\Populate\Exception\RuntimeException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class PropertiesExtractor implements PropertiesExtractorInterface
{
    /**
     * @inheritDoc
     */
    public function getProperties(string $class): array
    {
        try {
            $ref = new ReflectionClass($class);
        } catch (ReflectionException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return array_values(
            array_map(
                function(ReflectionProperty $property) {
                    return $property->getName();
                },
                array_filter(
                    $ref->getProperties(),
                    function (ReflectionProperty $property) {
                        return !$property->isStatic();
                    }
                )
            )
        );
    }
}