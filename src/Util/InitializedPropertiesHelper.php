<?php /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */

declare(strict_types=1);

namespace Kenny1911\Populate\Util;

use InvalidArgumentException;
use ReflectionException;
use ReflectionProperty;
use RuntimeException;

if (version_compare(phpversion(), '7.4.0', '>=')) {
    /**
     * @deprecated since "kenny1911/populate" v0.4.0, use "kenny1911/typed-properties-helper" package.
     */
    class InitializedPropertiesHelper
    {
        /**
         * @param object $obj
         * @param string $prop
         *
         * @return bool
         *
         * @deprecated since "kenny1911/populate" v0.4.0, use "kenny1911/typed-properties-helper" package.
         */
        public static function isInitialized($obj, string $prop): bool
        {
            trigger_error('Method InitializedPropertiesHelper::isInitialized() is deprecated since "kenny1911/populate" v0.4.0. It will removed in v1.0.0. Use "kenny1911/typed-properties-helper" package.', E_USER_DEPRECATED);

            $ref = static::getProperty($obj, $prop);
            $ref->setAccessible(true);

            return $ref->isInitialized($obj);
        }

        /**
         * @param object $obj
         * @param string $prop
         *
         * @return bool
         *
         * @deprecated since "kenny1911/populate" v0.4.0, use "kenny1911/typed-properties-helper" package.
         */
        public static function isTyped($obj, string $prop): bool
        {
            trigger_error('Method InitializedPropertiesHelper::isTyped() is deprecated since "kenny1911/populate" v0.4.0. It will removed in v1.0.0. Use "kenny1911/typed-properties-helper" package.', E_USER_DEPRECATED);

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