<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor;

use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\CallablePropertiesExtractor;
use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractor;
use PHPUnit\Framework\TestCase;

class CallablePropertiesExtractorTest extends TestCase
{
    public function test()
    {
        $extractor = new CallablePropertiesExtractor(
            new PropertiesExtractor(),
            function(string $property) {
                return in_array($property, ['foo', 'baz']);
            }
        );

        $obj = new class {
            public $foo;
            public $bar;
            public $baz;
            public $qux;
        };

        $this->assertSame(['foo', 'baz'], $extractor->getProperties(get_class($obj)));
    }
}
