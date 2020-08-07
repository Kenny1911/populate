<?php

declare(strict_types=1);

use Kenny1911\Populate\AdvancedPopulate;
use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\Populate;
use Kenny1911\Populate\SettingsStorage\SettingsStorage;
use Kenny1911\Populate\PropertyAccessor\ReflectionPropertyAccessor;
use PHPUnit\Framework\TestCase;

class AdvancedPopulateTest extends TestCase
{
    /** @var object */
    private $src;

    /** @var object */
    private $dest;

    /** @var SettingsStorage */
    private $settings;

    /** @var AdvancedPopulate */
    private $populate;

    public function testPopulate()
    {
        $this->populate->populate($this->src, $this->dest);

        $this->assertSame(123, $this->dest->public);
        $this->assertSame(456, $this->dest->getProtected());
        $this->assertSame(789, $this->dest->getPrivate());
        $this->assertNull($this->dest->foo);
        $this->assertNull($this->dest->getBar());
        $this->assertNull($this->dest->getBaz());
    }

    public function testPopulateWithSettings()
    {
        $this->settings->setProperties($this->src, $this->dest, ['public', 'private']);
        $this->settings->setMapping($this->src, $this->dest, ['protected' => 'bar', 'private' => 'baz']);

        $this->populate->populate($this->src, $this->dest);

        $this->assertSame(123, $this->dest->public);
        $this->assertNull($this->dest->getProtected());
        $this->assertNull($this->dest->getPrivate());
        $this->assertNull($this->dest->foo);
        $this->assertNull($this->dest->getBar());
        $this->assertSame(789, $this->dest->getBaz());
    }

    public function testPopulateOverrideSettings()
    {
        $this->settings->setProperties($this->src, $this->dest, ['public', 'private']);
        $this->settings->setMapping($this->src, $this->dest, ['protected' => 'bar', 'private' => 'baz']);

        $properties = ['public', 'protected'];
        $mapping = ['public' => 'foo', 'protected' => 'protected'];
        $this->populate->populate($this->src, $this->dest, $properties, $mapping);

        $this->assertNull($this->dest->public);
        $this->assertSame(456, $this->dest->getProtected());
        $this->assertNull($this->dest->getPrivate());
        $this->assertSame(123, $this->dest->foo);
        $this->assertNull($this->dest->getBar());
        $this->assertNull($this->dest->getBaz());


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

        $this->dest = new class {
            public $public;
            protected $protected;
            private $private;

            public $foo;
            protected $bar;
            private $baz;

            public function getProtected()
            {
                return $this->protected;
            }

            public function getPrivate()
            {
                return $this->private;
            }

            public function getBar()
            {
                return $this->bar;
            }

            public function getBaz()
            {
                return $this->baz;
            }
        };

        $this->settings = new SettingsStorage();
        $this->populate = new AdvancedPopulate(
            new Populate(
                new ObjectAccessor(
                    new ReflectionPropertyAccessor()
                )
            ),
            $this->settings
        );
    }
}
