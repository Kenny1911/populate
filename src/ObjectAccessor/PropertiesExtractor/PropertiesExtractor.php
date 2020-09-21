<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor;

use Kenny1911\Populate\Exception\RuntimeException;
use ReflectionClass;
use ReflectionException;

class PropertiesExtractor implements PropertiesExtractorInterface
{
    /**
     * @inheritDoc
     */
    public function getProperties($src): array
    {
        try {
            $class = new ReflectionClass($src);
        } catch (ReflectionException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $class->getProperties();
    }
}