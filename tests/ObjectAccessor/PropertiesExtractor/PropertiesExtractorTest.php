<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor;

use Kenny1911\Populate\Exception\RuntimeException;
use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractor;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class PropertiesExtractorTest extends TestCase
{

    /** @noinspection PhpUnusedPrivateFieldInspection */
    public function test()
    {
        $src = new class {
            public $foo;
            protected $bar;
            private $baz;
        };

        $extractor = new PropertiesExtractor();

        $this->assertSame(
            ['foo', 'bar', 'baz'],
            array_map(
                function (ReflectionProperty $property) {
                    return $property->getName();
                },
                $extractor->getProperties($src)
            )
        );
    }

    public function testClassNotExist()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Class Invalid does not exist');

        $extractor = new PropertiesExtractor();
        /** @noinspection PhpParamsInspection */
        $extractor->getProperties('Invalid');
    }
}
