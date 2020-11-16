<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor;

use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
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

        $expected = [
            'public' => 321,
            'protected' => 456,
            'private' => 987
        ];

        $this->assertSame($expected, $this->accessor->getData($this->src));
    }

    protected function setUp(): void
    {
        $this->src = new class {
            public $public = 123;
            protected $protected = 456;
            private $private = 789;

            public function getProtected(): int
            {
                return $this->protected;
            }

            public function getPrivate(): int
            {
                return $this->private;
            }

            public function setPrivate(int $value): void
            {
                $this->private = $value;
            }
        };

        $this->accessor = new ObjectAccessor(PropertyAccess::createPropertyAccessor(), new ReflectionExtractor());
    }
}
