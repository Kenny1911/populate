<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor;

use ReflectionProperty;

interface PropertiesExtractorInterface
{
    /**
     * @param object $src
     * @return ReflectionProperty[]
     */
    public function getProperties($src): array;
}