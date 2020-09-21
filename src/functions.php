<?php /** @noinspection PhpUndefinedClassInspection */

declare(strict_types=1);

namespace Kenny1911\Populate;

use Kenny1911\Populate\Util\InitializedPropertiesHelper;

if (version_compare(phpversion(), '7.4.0', '>=')) {
    if (!function_exists('is_initialized')) {
        /**
         * @param object $obj
         * @param string $prop
         * @return bool
         */
        function is_initialized($obj, string $prop): bool
        {
            return InitializedPropertiesHelper::isInitialized($obj, $prop);
        }
    }

    if (!function_exists('is_typed')) {
        /**
         * @param object $obj
         * @param string $prop
         * @return bool
         */
        function is_typed($obj, string $prop): bool
        {
            return InitializedPropertiesHelper::isTyped($obj, $prop);
        }
    }
}
