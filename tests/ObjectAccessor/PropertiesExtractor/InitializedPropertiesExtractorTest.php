<?php /** @noinspection PhpLanguageLevelInspection */

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor;

use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\InitializedPropertiesExtractor;
use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractor;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

if (version_compare(phpversion(), '7.4.0', '>=')) {
    class InitializedPropertiesExtractorTest extends TestCase
    {
        public function test()
        {
            $src = new class {
                public $foo = 'Foo';
                public string $bar = 'Bar';
                public string $baz;
            };

            $internalExtractor = new PropertiesExtractor();
            $extractor = new InitializedPropertiesExtractor($internalExtractor);

            $this->assertSame(['foo', 'bar', 'baz'], $this->filterProperties($internalExtractor->getProperties($src)));
            $this->assertSame(['foo', 'bar'], $this->filterProperties($extractor->getProperties($src)));

            unset($src->foo);

            $this->assertSame(['foo', 'bar'], $this->filterProperties($extractor->getProperties($src)));
        }

        /**
         * @param ReflectionProperty[] $properties
         * @return string[]
         */
        private function filterProperties(array $properties): array
        {
            return array_map(
                function (ReflectionProperty $property) {
                    return $property->getName();
                },
                $properties
            );
        }
    }
}
