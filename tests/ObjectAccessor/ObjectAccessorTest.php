<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor;

use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\Tests\Src;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

class ObjectAccessorTest extends TestCase
{
    /** @var object */
    private $src;

    /** @var ObjectAccessor */
    private $accessor;

    public function testGetData()
    {
        $expected = [
            'public' => 123,
            'protected' => 456,
            'private' => 789
        ];

        $this->assertSame($expected, $this->accessor->getData($this->src));
    }

    public function testGetDataByPropertiesAndMapping()
    {
        $expected = [
            'bar' => 789
        ];

        $properties = ['public', 'private'];
        $ignoreProperties = ['public'];
        $mapping = ['public' => 'foo', 'private' => 'bar'];
        $this->assertSame($expected, $this->accessor->getData($this->src, $properties, $ignoreProperties, $mapping));
    }

    public function testSetData()
    {
        $data = [
            'public' => 321,
            'protected' => 654,
            'private' => 987,
            'invalid' => 000,
        ];

        $this->accessor->setData($this->src, $data);

        $this->assertSame(321, $this->src->public);
        $this->assertSame(654, $this->src->getProtected());
        $this->assertSame(987, $this->src->getPrivate());
    }

    protected function setUp(): void
    {
        $this->src = new Src(123, 456, 789);

        $this->accessor = new ObjectAccessor(PropertyAccess::createPropertyAccessor(), new ReflectionExtractor());
    }
}
