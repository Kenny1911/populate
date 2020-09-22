<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor;

use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\CallablePropertiesExtractor;
use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractor;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class CallablePropertiesExtractorTest extends TestCase
{
    public function test()
    {
        $extractor = new CallablePropertiesExtractor(
            new PropertiesExtractor(),
            function(ReflectionProperty $property) {
                return in_array($property->getName(), ['foo', 'baz']);
            }
        );

        $obj = new class {
            public $foo;
            public $bar;
            public $baz;
            public $qux;
        };

        $properties = array_map(
            function (ReflectionProperty $property) {
                return $property->getName();
            },
            $extractor->getProperties($obj)
        );

        $this->assertSame(['foo', 'baz'], $properties);
    }
}
