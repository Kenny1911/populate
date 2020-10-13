<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor;

interface PropertiesExtractorInterface
{
    /**
     * @param string $class
     * @return string[]
     */
    public function getProperties(string $class): array;
}