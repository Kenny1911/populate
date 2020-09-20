<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\PropertyAccessor;

use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotReadableException;
use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotWritableException;
use Kenny1911\Populate\PropertyAccessor\ReflectionPropertyAccessor;
use PHPUnit\Framework\TestCase;

class ReflectionPropertyAccessorTest extends TestCase
{
    /** @var ReflectionPropertyAccessor */
    private $accessor;

    /** @var object */
    private $src;

    public function testGetValue()
    {
        $this->assertSame(123, $this->accessor->getValue($this->src, 'public'));
        $this->assertSame(456, $this->accessor->getValue($this->src, 'protected'));
        $this->assertSame(789, $this->accessor->getValue($this->src, 'private'));
    }

    public function testGetValuePropertyNotReadableException()
    {
        $this->expectException(PropertyNotReadableException::class);
        $this->expectExceptionMessage(sprintf('Property %s::$invalid is not readable.', get_class($this->src)));

        $this->accessor->getValue($this->src, 'invalid');
    }

    public function testSetValue()
    {
        $this->accessor->setValue($this->src, 'public', 321);
        $this->accessor->setValue($this->src, 'protected', 654);
        $this->accessor->setValue($this->src, 'private', 987);

        $this->assertSame(321, $this->src->public);
        $this->assertSame(654, $this->src->getProtected());
        $this->assertSame(987, $this->src->getPrivate());
    }

    public function testSetValuePropertyNotWritableException()
    {
        $this->expectException(PropertyNotWritableException::class);
        $this->expectExceptionMessage(sprintf('Property %s::$invalid is not writable.', get_class($this->src)));

        $this->accessor->setValue($this->src, 'invalid', 123);
    }

    public function testIsReadable()
    {
        $this->assertTrue($this->accessor->isReadable($this->src, 'public'));
        $this->assertTrue($this->accessor->isReadable($this->src, 'protected'));
        $this->assertTrue($this->accessor->isReadable($this->src, 'private'));
        $this->assertFalse($this->accessor->isReadable($this->src, 'invalid'));
    }

    public function testIsWritable()
    {
        $this->assertTrue($this->accessor->isWritable($this->src, 'public'));
        $this->assertTrue($this->accessor->isWritable($this->src, 'protected'));
        $this->assertTrue($this->accessor->isWritable($this->src, 'private'));
        $this->assertFalse($this->accessor->isWritable($this->src, 'invalid'));
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

        $this->accessor = new ReflectionPropertyAccessor();
    }
}
