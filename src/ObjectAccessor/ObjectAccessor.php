<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyListExtractorInterface;

class ObjectAccessor implements ObjectAccessorInterface
{
    private $accessor;

    private $propertiesExtractor;

    public function __construct(
        PropertyAccessorInterface $accessor,
        PropertyListExtractorInterface $propertiesExtractor
    )
    {
        $this->accessor = $accessor;
        $this->propertiesExtractor = $propertiesExtractor;
    }

    /**
     * @inheritDoc
     */
    public function getData(
        $src,
        array $properties = [],
        array $ignoreProperties = [],
        array $mapping = []
    ): array
    {
        $allProperties = (array)$this->propertiesExtractor->getProperties(get_class($src));

        $properties = $properties ?: $allProperties;

        $data = [];

        $properties = array_filter(
            $allProperties,
            function(string $property) use ($properties, $ignoreProperties) {
                return in_array($property, $properties) && !in_array($property, $ignoreProperties);
            }
        );

        foreach ($properties as $property) {
            if ($this->accessor->isReadable($src, $property)) {
                $key = $mapping[$property] ?? $property;
                $data[$key] = $this->accessor->getValue($src, $property);
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
}