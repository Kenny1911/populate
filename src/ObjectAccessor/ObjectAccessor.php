<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor;

use Kenny1911\Populate\Exception\RuntimeException;
use Kenny1911\Populate\PropertyAccessor\PropertyAccessorInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class ObjectAccessor implements ObjectAccessorInterface
{
    /** @var PropertyAccessorInterface */
    protected $accessor;

    public function __construct(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @inheritDoc
     */
    public function getData($src, ?array $properties = null, array $mapping = []): array
    {
        $data = [];

        foreach ($this->getProperties($src) as $prop) {
            if ((is_null($properties) || in_array($prop, $properties)) && $this->accessor->isReadable($src, $prop)) {
                $key = $mapping[$prop] ?? $prop;
                $data[$key] = $this->accessor->getValue($src, $prop);
            }
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function setData($dest, array $data): void
    {
        foreach ($data as $prop => $value) {
            if ($this->accessor->isWritable($dest, $prop)) {
                $this->accessor->setValue($dest, $prop, $value);
            }
        }
    }

    /**
     * @param object $src
     *
     * @return string[]
     */
    protected function getProperties($src): array
    {
        try {
            $class = new ReflectionClass($src);
        } catch (ReflectionException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return array_map(
            function (ReflectionProperty $prop) {
                $prop->getName();
            },
            $class->getProperties()
        );
    }
}