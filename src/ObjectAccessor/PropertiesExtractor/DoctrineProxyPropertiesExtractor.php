<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor;

use Doctrine\Persistence\Proxy;
use Kenny1911\Populate\Exception\LogicException;
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

        /**
         * @inheritDoc
         * @throws ReflectionException
         */
        public function getProperties(string $class): array
        {
            if (is_subclass_of($class, Proxy::class, true)) {
                $parent = (new ReflectionClass($class))->getParentClass();

                if (!$parent) {
                    throw new LogicException(
                        sprintf('Doctrine proxy class "%s" hasn\'t parent class', $class)
                    );
                }

                $class = $parent->getName();
            }

            return $this->internal->getProperties($class);
        }
    }
}