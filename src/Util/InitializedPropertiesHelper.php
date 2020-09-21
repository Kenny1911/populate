<?php /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */

declare(strict_types=1);

namespace Kenny1911\Populate\Util;

use Kenny1911\Populate\Exception\InvalidArgumentException;
use Kenny1911\Populate\Exception\RuntimeException;
use ReflectionException;
use ReflectionProperty;

if (version_compare(phpversion(), '7.4.0', '>=')) {
    class InitializedPropertiesHelper
    {
        /**
         * @param object $obj
         * @param string $prop
         *
         * @return bool
         */
        public static function isInitialized($obj, string $prop): bool
        {
            return static::getProperty($obj, $prop)->isInitialized($obj);
        }

        /**
         * @param object $obj
         * @param string $prop
         *
         * @return bool
         */
        public static function isTyped($obj, string $prop): bool
        {
            return static::getProperty($obj, $prop)->hasType();
        }

        /**
         * @param object $obj
         * @param string $prop
         *
         * @return ReflectionProperty
         */
        private static function getProperty($obj, string $prop): ReflectionProperty
        {
            if (!is_object($obj)) {
                throw new InvalidArgumentException('Argument $obj passed must be of the type object.');
            }

            try {
                return new ReflectionProperty($obj, $prop);
            } catch (ReflectionException $e) {
                throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
            }
        }
    }
}