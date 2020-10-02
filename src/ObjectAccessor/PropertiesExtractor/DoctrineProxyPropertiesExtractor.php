<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor;

use Doctrine\Persistence\Proxy;
use Kenny1911\Populate\Exception\RuntimeException;
use ReflectionClass;
use ReflectionException;

if (interface_exists(Proxy::class)) {
    class DoctrineProxyPropertiesExtractor implements PropertiesExtractorInterface
    {
        private $internal;

        public function __construct(PropertiesExtractorInterface $internal)
        {
            $this->internal = $internal;
        }

        public function getProperties($src): array
        {
            if ($src instanceof Proxy) {
                try {
                    $class = new ReflectionClass($src);
                } catch (ReflectionException $e) {
                    throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
                }

                $parent = $class->getParentClass();

                if (!$parent) {
                    throw new RuntimeException(
                        sprintf('Doctrine proxy class "%s" hasn\'t parent class', $class->getName())
                    );
                }

                return $parent->getProperties();
            }

            return $this->internal->getProperties($src);
        }
    }
}