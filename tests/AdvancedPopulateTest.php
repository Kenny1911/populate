<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests;

use Kenny1911\Populate\AdvancedPopulate;
use Kenny1911\Populate\ObjectAccessor\ObjectAccessor;
use Kenny1911\Populate\Populate;
use Kenny1911\Populate\Settings\Settings;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

class AdvancedPopulateTest extends TestCase
{
    public function testPopulate()
    {
        $src = new Src(123, 456, 789);
        $dest = new Dest();

        $this->createPopulate()->populate($src, $dest);

        $this->assertNull($dest->foo);
        $this->assertNull($dest->getBar());
        $this->assertNull($dest->getBaz());
    }

    public function testPopulateWithSettings()
    {
        $src = new Src(123, 456, 789);
        $dest = new Dest();

        $settings = [
            [
                'src' => Src::class,
                'dest' => Dest::class,
                'properties' => ['public', 'private'],
                'mapping' => ['protected' => 'bar', 'private' => 'baz']
            ]
        ];

        $this->createPopulate($settings)->populate($src, $dest);

        $this->assertNull($dest->foo);
        $this->assertNull($dest->getBar());
        $this->assertSame(789, $dest->getBaz());
    }

    public function testPopulateOverrideSettings()
    {
        $src = new Src(123, 456, 789);
        $dest = new Dest();

        $settings = [
            [
                'src' => Src::class,
                'dest' => Dest::class,
                'properties' => ['public', 'private'],
                'mapping' => ['protected' => 'bar', 'private' => 'baz']
            ]
        ];

        $properties = ['public', 'protected'];
        $ignoreProperties = ['protected'];
        $mapping = ['public' => 'foo', 'protected' => 'protected'];

        $this->createPopulate($settings)->populate($src, $dest, $properties, $ignoreProperties, $mapping);

        $this->assertSame(123, $dest->foo);
        $this->assertNull($dest->getBar());
        $this->assertNull($dest->getBaz());
    }

    public function testPopulateFromArray()
    {
        $src = ['foo' => 123, 'bar' => 456, 'baz' => 789];
        $dest = new Dest();

        $this->createPopulate()->populate($src, $dest);

        $this->assertSame(123, $dest->foo);
        $this->assertSame(456, $dest->getBar());
        $this->assertSame(789, $dest->getBaz());
    }

    private function createPopulate(array $settings = []): AdvancedPopulate
    {
        return new AdvancedPopulate(
            new Populate(
                new ObjectAccessor(
                    PropertyAccess::createPropertyAccessor(),
                    new ReflectionExtractor()
                )
            ),
            new Settings($settings)
        );
    }
}
