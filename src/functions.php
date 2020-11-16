<?php /** @noinspection PhpUndefinedClassInspection */

declare(strict_types=1);

namespace Kenny1911\Populate;

use Kenny1911\Populate\Util\InitializedPropertiesHelper;

if (version_compare(phpversion(), '7.4.0', '>=')) {
    if (!function_exists(__NAMESPACE__.'\is_initialized')) {
        /**
         * @param object $obj
         * @param string $prop
         * @return bool
         *
         * @deprecated since "kenny1911/populate" v0.4.0, use "kenny1911/typed-properties-helper" package.
         */
        function is_initialized($obj, string $prop): bool
        {
            return InitializedPropertiesHelper::isInitialized($obj, $prop);
        }
    }

    if (!function_exists(__NAMESPACE__.'\is_typed')) {
        /**
         * @param object $obj
         * @param string $prop
         * @return bool
         *
         * @deprecated since "kenny1911/populate" v0.4.0, use "kenny1911/typed-properties-helper" package.
         */
        function is_typed($obj, string $prop): bool
        {
            return InitializedPropertiesHelper::isTyped($obj, $prop);
        }
    }
}
