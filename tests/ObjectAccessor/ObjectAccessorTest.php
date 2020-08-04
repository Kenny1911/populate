<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\ObjectAccessor;

use Kenny1911\Populate\Exception\RuntimeException;
use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\PropertyAccessor\ReflectionPropertyAccessor;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

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
            'foo' => 123,
            'bar' => 789
        ];

        $properties = ['public', 'private'];
        $mapping = ['public' => 'foo', 'private' => 'bar'];
        $this->assertSame($expected, $this->accessor->getData($this->src, $properties, $mapping));
    }

    public function testSetData()
    {
        $data = [
            'public' => 321,
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

    public function testGetPropertiesRuntimeException()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Class Invalid does not exist');

        /** @noinspection PhpUnhandledExceptionInspection */
        $method = new ReflectionMethod($this->accessor, 'getProperties');
        $method->setAccessible(true);
        $method->invoke($this->accessor, 'Invalid');
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
        };

        $this->accessor = new ObjectAccessor(new ReflectionPropertyAccessor());
    }
}
