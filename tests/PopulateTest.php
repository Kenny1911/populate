<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests;

use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\Populate;
use Kenny1911\Populate\PropertyAccessor\ReflectionPropertyAccessor;
use PHPUnit\Framework\TestCase;

class PopulateTest extends TestCase
{
    /** @var object */
    private $src;

    /** @var Populate */
    private $populate;

    public function testPopulate()
    {
        $dest = new class {
            public $public;
            private $private;

            public function getPrivate()
            {
                return $this->private;
            }
        };

        $this->populate->populate($this->src, $dest);

        $this->assertSame(123, $dest->public);
        $this->assertSame(789, $dest->getPrivate());
    }

    public function testPopulateByPropertiesAndMapping()
    {
        $dest = new class {
            public $foo;
            protected $bar;
            private $baz;

            public function getBar()
            {
                return $this->bar;
            }

            public function getBaz()
            {
                return $this->baz;
            }
        };

        $properties = ['public', 'private'];
        $mapping = ['public' => 'foo', 'protected' => 'bar', 'private' => 'baz'];
        $this->populate->populate($this->src, $dest, $properties, $mapping);

        $this->assertSame(123, $dest->foo);
        $this->assertNull($dest->getBar());
        $this->assertSame(789, $dest->getBaz());
    }

    public function testPopulateFromArray()
    {
        $src = ['public' => 123, 'protected' => 456, 'private' => 789];

        $dest = new class {
            public $public;
            protected $protected;
            private $private;

            public function getProtected()
            {
                return $this->protected;
            }

            public function getPrivate()
            {
                return $this->private;
            }
        };

        $this->populate->populate($src, $dest);

        $this->assertSame(123, $dest->public);
        $this->assertSame(456, $dest->getProtected());
        $this->assertSame(789, $dest->getPrivate());
    }

    protected function setUp(): void
    {
        $this->src = new class {
            public $public = 123;
            protected $protected = 456;
            private $private = 789;

            public function getProtected()
            {
                return $this->protected;
            }

            public function getPrivate()
            {
                return $this->private;
            }
        };

        $this->populate = new Populate(new ObjectAccessor(new ReflectionPropertyAccessor()));
    }
}
