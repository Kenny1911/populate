<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor;

use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\NonStaticPropertiesExtractor;
use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractor;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class NonStaticPropertiesExtractorTest extends TestCase
{
    public function test()
    {
        $extractor = new NonStaticPropertiesExtractor(new PropertiesExtractor());

        $obj = new class {
            public $foo;

            public static $bar;
        };

        $properties = array_map(
            function(ReflectionProperty $property) {
                return $property->getName();
            },
            $extractor->getProperties($obj)
        );

        $this->assertSame(['foo'], $properties);
    }
}
