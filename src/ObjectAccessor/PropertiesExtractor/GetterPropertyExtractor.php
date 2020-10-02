<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor;

use Kenny1911\Populate\Exception\RuntimeException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class GetterPropertyExtractor implements PropertiesExtractorInterface
{
    private $internal;

    public function __construct(PropertiesExtractorInterface $internal)
    {
        $this->internal = $internal;
    }

    /**
     * @param object $src
     * @return array
     */
    public function getProperties($src): array
    {
        try {
            $class = new ReflectionClass($src);
        } catch (ReflectionException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return array_merge(
            $this->internal->getProperties($src),
            array_map(
                function(ReflectionMethod $method) {
                    return new GetterReflectionProperty($method);
                },
                array_filter(
                    $class->getMethods(ReflectionMethod::IS_PUBLIC),
                    function (ReflectionMethod $method) {
                        return GetterReflectionProperty::isGetter($method);
                    }
                )
            )
        );
    }
}