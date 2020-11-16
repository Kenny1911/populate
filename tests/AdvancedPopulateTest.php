<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests;

use Kenny1911\Populate\AdvancedPopulate;
use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\Populate;
use Kenny1911\Populate\SettingsStorage\SettingsStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

class AdvancedPopulateTest extends TestCase
{
    private $src;

    private $dest;

    /** @var SettingsStorage */
    private $settings;

    /** @var AdvancedPopulate */
    private $populate;

    public function testPopulate()
    {
        $this->populate->populate($this->src, $this->dest);

        $this->assertNull($this->dest->foo);
        $this->assertNull($this->dest->getBar());
        $this->assertNull($this->dest->getBaz());
    }

    public function testPopulateWithSettings()
    {
        $this->settings->setProperties($this->src, $this->dest, ['public', 'private']);
        $this->settings->setMapping($this->src, $this->dest, ['protected' => 'bar', 'private' => 'baz']);

        $this->populate->populate($this->src, $this->dest);

        $this->assertNull($this->dest->foo);
        $this->assertNull($this->dest->getBar());
        $this->assertSame(789, $this->dest->getBaz());
    }

    public function testPopulateOverrideSettings()
    {
        $this->settings->setProperties($this->src, $this->dest, ['public', 'private']);
        $this->settings->setMapping($this->src, $this->dest, ['protected' => 'bar', 'private' => 'baz']);

        $properties = ['public', 'protected'];
        $ignoreProperties = ['protected'];
        $mapping = ['public' => 'foo', 'protected' => 'protected'];
        $this->populate->populate($this->src, $this->dest, $properties, $ignoreProperties, $mapping);

        $this->assertSame(123, $this->dest->foo);
        $this->assertNull($this->dest->getBar());
        $this->assertNull($this->dest->getBaz());


    }

    protected function setUp(): void
    {
        $this->src = new Src(123, 456, 789);
        $this->dest = new Dest();

        $this->settings = new SettingsStorage();
        $this->populate = new AdvancedPopulate(
            new Populate(
                new ObjectAccessor(
                    PropertyAccess::createPropertyAccessor(),
                    new ReflectionExtractor()
                )
            ),
            $this->settings
        );
    }
}
