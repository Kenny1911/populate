<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\PropertyAccessor;

use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotReadableException;
use Kenny1911\Populate\Exception\PropertyAccessor\PropertyNotWritableException;
use Kenny1911\Populate\PropertyAccessor\SymfonyPropertyAccessor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SymfonyPropertyAccessorTest extends TestCase
{
    /** @var object */
    private $src;

    /** @var SymfonyPropertyAccessor */
    private $accessor;

    public function testGetValue()
    {
        $this->assertSame(123, $this->accessor->getValue($this->src, 'public'));
        $this->assertSame(456, $this->accessor->getValue($this->src, 'protected'));
        $this->assertSame(789, $this->accessor->getValue($this->src, 'private'));
    }

    public function testGetValuePropertyNotReadableException()
    {
        $this->expectException(PropertyNotReadableException::class);
        $this->expectErrorMessage(sprintf('Property %s::$notAvailable is not readable.', get_class($this->src)));

        $this->accessor->getValue($this->src, 'notAvailable');
    }

    public function testIsReadable()
    {
        $this->assertTrue($this->accessor->isReadable($this->src, 'public'));
        $this->assertTrue($this->accessor->isReadable($this->src, 'protected'));
        $this->assertTrue($this->accessor->isReadable($this->src, 'private'));
        $this->assertFalse($this->accessor->isReadable($this->src, 'notAvailable'));
    }

    public function testSetValue()
    {
        $this->accessor->setValue($this->src, 'public', 321);
        $this->accessor->setValue($this->src, 'protected', 654);
        $this->accessor->setValue($this->src, 'private', 987);

        $this->assertSame(321, $this->accessor->getValue($this->src, 'public'));
        $this->assertSame(654, $this->accessor->getValue($this->src, 'protected'));
        $this->assertSame(987, $this->accessor->getValue($this->src, 'private'));
    }

    public function testSetValuePropertyNotWritableException()
    {
        $this->expectException(PropertyNotWritableException::class);
        $this->expectErrorMessage(sprintf('Property %s::$notAvailable is not writable.', get_class($this->src)));

        $this->accessor->setValue($this->src, 'notAvailable', 111);
    }

    public function testIsWritable()
    {
        $this->assertTrue($this->accessor->isWritable($this->src, 'public'));
        $this->assertTrue($this->accessor->isWritable($this->src, 'protected'));
        $this->assertTrue($this->accessor->isWritable($this->src, 'private'));
        $this->assertFalse($this->accessor->isWritable($this->src, 'notAvailable'));
    }

    protected function setUp(): void
    {
        $this->src = new class {
            public $public = 123;
            protected $protected = 456;
            private $private = 789;
            private $notAvailable = 000;

            public function getProtected(): int
            {
                return $this->protected;
            }

            public function setProtected($value)
            {
                $this->protected = $value;
            }

            public function getPrivate(): int
            {
                return $this->private;
            }

            public function setPrivate($value)
            {
                $this->private = $value;
            }

            public function getNotAvailableHiddenGetter()
            {
                return $this->notAvailable;
            }
        };

        $this->accessor = new SymfonyPropertyAccessor(PropertyAccess::createPropertyAccessor());
    }
}
