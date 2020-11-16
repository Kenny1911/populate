<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor;

use ReflectionClass;
use ReflectionMethod;

class GetterPropertyExtractor implements PropertiesExtractorInterface
{
    private const GETTER_PATTERN = '/^(get|has|is)(\w+)$/';

    private $internal;

    public function __construct(PropertiesExtractorInterface $internal)
    {
        $this->internal = $internal;
    }

    /**
     * @inheritDoc
     */
    public function getProperties(string $class): array
    {
        $ref = new ReflectionClass($class);

        return array_unique(
            array_merge(
                $this->internal->getProperties($class),
                array_map(
                    function(ReflectionMethod $method) {
                        preg_match(static::GETTER_PATTERN, $method->getName(), $matches);

                        return lcfirst($matches[2]);
                    },
                    array_filter(
                        $ref->getMethods(ReflectionMethod::IS_PUBLIC),
                        function (ReflectionMethod $method) {
                            return (
                                $method->isPublic() &&
                                !$method->isStatic() &&
                                !$method->isAbstract() &&
                                (bool)preg_match(static::GETTER_PATTERN, $method->getName()) &&
                                0 === $method->getNumberOfRequiredParameters()
                            );
                        }
                    )
                )
            )
        );
    }
}