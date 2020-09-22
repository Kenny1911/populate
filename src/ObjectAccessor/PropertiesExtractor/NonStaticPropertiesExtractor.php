<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor;

use ReflectionProperty;

class NonStaticPropertiesExtractor implements PropertiesExtractorInterface
{
    private $internal;

    public function __construct(PropertiesExtractorInterface $internal)
    {
        $this->internal = $internal;
    }

    /**
     * @inheritDoc
     */
    public function getProperties($src): array
    {
        return array_values(
            array_filter(
                $this->internal->getProperties($src),
                function(ReflectionProperty $property) {
                    return !$property->isStatic();
                }
            )
        );
    }
}