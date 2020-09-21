<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\PropertyAccessor;

use Kenny1911\Populate\Exception\InvalidArgumentException;
use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotReadableException;
use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotWritableException;
use Kenny1911\Populate\PropertyAccessor\ChainPropertyAccessor;
use Kenny1911\Populate\PropertyAccessor\PropertyAccessorInterface;
use Kenny1911\Populate\PropertyAccessor\ReflectionPropertyAccessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChainPropertyAccessor1Test extends TestCase
{
    private $src;

    /** @var PropertyAccessorInterface|MockObject  */
    private $mockedAccessor;

    public function testCreateEmptyAccessorsArray()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage('Empty array with property accessors.');

        new ChainPropertyAccessor([]);
    }

    public function testCreateInvalidAccessorType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectErrorMessage(sprintf('Property accessor must implements "%s".', PropertyAccessorInterface::class));

        /** @noinspection PhpParamsInspection */
        new ChainPropertyAccessor(['invalid']);
    }

    public function testGetValue()
    {
        $accessor = new ChainPropertyAccessor([new ReflectionPropertyAccessor()]);

        $this->assertSame('bar', $accessor->getValue($this->src, 'foo'));
    }

    public function testGetValueDisabledExceptions()
    {
        $accessor = new ChainPropertyAccessor([new ReflectionPropertyAccessor()], true);

        $this->assertNull($accessor->getValue($this->src, 'invalid'));
    }

    public function testGetValuePropertyNotReadableException()
    {
        $this->expectException(PropertyNotReadableException::class);
        $this->expectExceptionMessage(sprintf('Property %s::$invalid is not readable.', get_class($this->src)));

        $accessor = new ChainPropertyAccessor([new ReflectionPropertyAccessor()]);

        $accessor->getValue($this->src, 'invalid');
    }

    public function testIsReadable()
    {
        $accessor = new ChainPropertyAccessor([new ReflectionPropertyAccessor()]);

        $this->assertTrue($accessor->isReadable($this->src, 'foo'));
        $this->assertFalse($accessor->isReadable($this->src, 'invalid'));
    }

    public function testSetValue()
    {
        $accessor = new ChainPropertyAccessor([new ReflectionPropertyAccessor()]);

        $accessor->setValue($this->src, 'foo', 'baz');
        $this->assertSame('baz', $this->src->foo);
    }

    public function testSetValueDisabledExceptions()
    {
        $accessor = new ChainPropertyAccessor([$this->mockedAccessor], true);

        $this->mockedAccessor
            ->expects($this->any())
            ->method('setValue')
            ->willThrowException(new PropertyNotWritableException(get_class($this->src), 'foo'))
        ;

        $accessor->setValue($this->src, 'foo', 'baz');

        $this->assertSame('bar', $this->src->foo);
    }

    public function testSetValuePropertyNotWritableException()
    {
        $this->expectException(PropertyNotWritableException::class);
        $this->expectExceptionMessage(sprintf('Property %s::$foo is not writable.', get_class($this->src)));

        $accessor = new ChainPropertyAccessor([$this->mockedAccessor]);

        $this->mockedAccessor
            ->expects($this->any())
            ->method('setValue')
            ->willThrowException(new PropertyNotWritableException(get_class($this->src), 'foo'))
        ;

        $accessor->setValue($this->src, 'foo', 'baz');
    }

    public function testIsWritable()
    {
        $accessor = new ChainPropertyAccessor([new ReflectionPropertyAccessor()]);

        $this->assertTrue($accessor->isWritable($this->src, 'foo'));
        $this->assertFalse($accessor->isWritable($this->src, 'invalid'));
    }

    protected function setUp(): void
    {
        $this->src = new class {
            public $foo = 'bar';
        };

        $this->mockedAccessor = $this->getMockBuilder(PropertyAccessorInterface::class)->getMockForAbstractClass();
    }
}
