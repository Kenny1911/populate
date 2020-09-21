<?php /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */

declare(strict_types=1);

namespace Kenny1911\Populate\Util;

use Error;
use Kenny1911\Populate\Exception\RuntimeException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;

/**
 * @codeCoverageIgnore
 */
if (version_compare(phpversion(), '7.4.0', '>=')) {
    trait InitializedPropertiesTrait
    {
        public function __construct()
        {
            $this->prepareUninitialized();
        }

        public function __get(string $name)
        {
            try {
                $property = new ReflectionProperty($this, $name);
            } catch (ReflectionException $e) {
                throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
            }

            if (!$property->hasType()) {
                return null;
            }

            $type = $property->getType();

            if ($type->allowsNull()) {
                return null;
            }

            $typeName = $type instanceof ReflectionNamedType ? $type->getName() : (string)$type;

            switch ($typeName) {
                case 'int':
                    return 0;

                case 'float':
                    return 0.0;

                case 'string':
                    return '';

                case 'bool':
                    return false;

                case 'array':
                    return [];
            }

            throw new Error(
                sprintf('Typed property %s::$%s must not be accessed before initialization', __CLASS__, $name)
            );
        }

        protected function prepareUninitialized()
        {
            /** @noinspection PhpUnhandledExceptionInspection */
            $class = new ReflectionClass($this);

            foreach ($class->getProperties() as $property) {
                if ($property->hasType() && !$property->isInitialized($this)) {
                    unset($this->{$property->getName()});
                }
            }
        }
    }
}