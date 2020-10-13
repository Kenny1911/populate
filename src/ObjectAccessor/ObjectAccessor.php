<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor;

use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractor;
use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractorInterface;
use Kenny1911\Populate\PropertyAccessor\PropertyAccessorInterface;

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

        $properties = $properties ?? $this->propertiesExtractor->getProperties(get_class($src));

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