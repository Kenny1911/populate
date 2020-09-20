<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor;

use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractor;
use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractorInterface;
use Kenny1911\Populate\PropertyAccessor\PropertyAccessorInterface;
use ReflectionProperty;

class ObjectAccessor implements ObjectAccessorInterface
{
    /** @var PropertyAccessorInterface */
    private $accessor;

    private $propertiesExtractor;

    public function __construct(
        PropertyAccessorInterface $accessor,
        ?PropertiesExtractorInterface $propertiesExtractor = null
    )
    {
        $this->accessor = $accessor;
        $this->propertiesExtractor = $propertiesExtractor ?? new PropertiesExtractor();
    }

    /**
     * @inheritDoc
     */
    public function getData($src, ?array $properties = null, array $mapping = []): array
    {
        $data = [];

        foreach ($this->getProperties($src, $properties) as $prop) {
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
     * @param string[]|null $filter
     *
     * @return string[]
     */
    protected function getProperties($src, ?array $filter): array
    {
        $properties = array_map(
            function (ReflectionProperty $prop) {
                return $prop->getName();
            },
            $this->propertiesExtractor->getProperties($src)
        );

        if (is_array($filter)) {
            $properties = array_filter($properties, function ($property) use (&$filter) {
                return in_array($property, $filter);
            });
        }

        return $properties;
    }
}