<?php

declare(strict_types=1);

namespace Kenny1911\Populate\PropertyAccessor;

use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotReadableException;
use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotWritableException;
use ReflectionException;
use ReflectionProperty;

class ReflectionPropertyAccessor implements PropertyAccessorInterface
{
    /** @var bool */
    private $disableExceptions;

    public function __construct(bool $disableExceptions = false)
    {
        $this->disableExceptions = $disableExceptions;
    }

    /**
     * @inheritDoc
     */
    public function getValue($src, string $name)
    {
        try {
            return $this->getProperty($src, $name)->getValue($src);
        } catch (ReflectionException $e) {
            if ($this->disableExceptions) {
                return null;
            } else {
                throw new PropertyNotReadableException(get_class($src), $name, $e->getCode(), $e);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function setValue($src, string $name, $value): void
    {
        try {
            $this->getProperty($src, $name)->setValue($src, $value);
        } catch (ReflectionException $e) {
            if(!$this->disableExceptions) {
                throw new PropertyNotWritableException(get_class($src), $name, $e->getCode(), $e);
            }
        }
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

    /**
     * @param $src
     * @param string $name
     * @return ReflectionProperty
     * @throws ReflectionException
     */
    private function getProperty($src, string $name): ReflectionProperty
    {
        $prop = new ReflectionProperty($src, $name);
        $prop->setAccessible(true);

        return $prop;
    }
}