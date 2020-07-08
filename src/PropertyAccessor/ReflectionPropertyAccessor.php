<?php

declare(strict_types=1);

namespace Kenny1911\Populate\PropertyAccessor;

use Kenny1911\Populate\Exception\RuntimeException;
use ReflectionException;
use ReflectionProperty;

class ReflectionPropertyAccessor implements PropertyAccessorInterface
{
    /**
     * @inheritDoc
     */
    public function getValue($src, string $name)
    {
        return $this->getProperty($src, $name)->getValue($src);
    }

    /**
     * @inheritDoc
     */
    public function setValue($src, string $name, $value): void
    {
        $this->getProperty($src, $name)->setValue($src, $value);
    }

    private function getProperty($src, string $name): ReflectionProperty
    {
        try {
            $prop = new ReflectionProperty($src, $name);
        } catch (ReflectionException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        $prop->setAccessible(true);

        return $prop;
    }

    /**
     * @inheritDoc
     */
    public function isReadable($src, string $name): bool
    {
        return property_exists($src, $name);
    }

    /**
     * @inheritDoc
     */
    public function isWritable($src, string $name): bool
    {
        return property_exists($src, $name);
    }
}