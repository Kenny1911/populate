<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests;

use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\Populate;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

class PopulateTest extends TestCase
{
    private $src;

    private $populate;

    public function testPopulate()
    {
        $dest = new Src();

        $this->populate->populate($this->src, $dest);

        $this->assertSame(123, $dest->public);
        $this->assertSame(456, $dest->getProtected());
        $this->assertSame(789, $dest->getPrivate());
    }

    public function testPopulateByPropertiesAndMapping()
    {
        $dest = new Dest();

        $properties = ['public', 'private'];
        $ignoreProperties = ['public'];
        $mapping = ['public' => 'foo', 'protected' => 'bar', 'private' => 'baz'];

        $this->populate->populate($this->src, $dest, $properties, $ignoreProperties, $mapping);

        $this->assertNull($dest->foo);
        $this->assertNull($dest->getBar());
        $this->assertSame(789, $dest->getBaz());
    }

    public function testPopulateFromArray()
    {
        $src = ['public' => 123, 'protected' => 456, 'private' => 789];

        $dest = new Src();

        $this->populate->populate($src, $dest);

        $this->assertSame(123, $dest->public);
        $this->assertSame(456, $dest->getProtected());
        $this->assertSame(789, $dest->getPrivate());
    }

    protected function setUp(): void
    {
        $this->src = new Src(123,456,789);

        $this->populate = new Populate(
            new ObjectAccessor(
                PropertyAccess::createPropertyAccessor(),
                new ReflectionExtractor()
            )
        );
    }
}
