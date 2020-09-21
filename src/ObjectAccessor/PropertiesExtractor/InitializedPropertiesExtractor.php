<?php /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor\PropertiesExtractor;

use ReflectionProperty;

if (version_compare(phpversion(), '7.4.0', '>=')) {
    /**
     * Decorator of PropertiesExtractorInterface for filtering object properties, that have uninitialized state.
     *
     * @since 7.4 Only typed properties can be uninitialized. This feature has been available since PHP version 7.4.
     */
    class InitializedPropertiesExtractor implements PropertiesExtractorInterface
    {
        private $internal;

        public function __construct(PropertiesExtractorInterface $internal)
        {
            $this->internal = $internal;
        }

        /**
         * @inheritDoc
         */
        public function getProperties($src): array
        {
            return array_values(
                array_filter(
                    $this->internal->getProperties($src),
                    function (ReflectionProperty $property) use ($src) {
                        $property->setAccessible(true);

                        return $property->hasType() ? $property->isInitialized($src) : true;
                    }
                )
            );
        }
    }
}