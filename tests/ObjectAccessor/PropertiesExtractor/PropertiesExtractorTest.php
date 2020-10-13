<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor;

use Kenny1911\Populate\Exception\RuntimeException;
use Kenny1911\Populate\ObjectAccessor\PropertiesExtractor\PropertiesExtractor;
use Kenny1911\Populate\Tests\ObjectAccessor\PropertiesExtractor\Entity\Entity;
use PHPUnit\Framework\TestCase;

class PropertiesExtractorTest extends TestCase
{
    /** @var PropertiesExtractor */
    private $extractor;

    public function test()
    {
        $this->assertSame(['foo', 'bar', 'baz'], $this->extractor->getProperties(Entity::class));
    }

    public function testClassNotExist()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Class Invalid does not exist');

        $this->extractor->getProperties('Invalid');
    }

    protected function setUp(): void
    {
        $this->extractor = new PropertiesExtractor();
    }
}
