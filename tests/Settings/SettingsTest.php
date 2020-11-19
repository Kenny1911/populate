<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\Settings;

use Kenny1911\Populate\Settings\Settings;
use Kenny1911\Populate\Tests\Dest;
use Kenny1911\Populate\Tests\Src;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    public function test()
    {
        $properties = ['public', 'private'];
        $ignoreProperties = ['private'];
        $mapping = ['public' => 'foo', 'protected' => 'bar', 'private' => 'baz'];

        $settings = new Settings([
            [
                'src' => Src::class,
                'dest' => Dest::class,
                'properties' => $properties,
                'ignore_properties' => $ignoreProperties,
                'mapping' => $mapping
            ]
        ]);

        $this->assertSame($properties, $settings->getProperties(Src::class, Dest::class));
        $this->assertSame($properties, $settings->getProperties(new Src(), new Dest()));
        $this->assertSame([], $settings->getProperties(Src::class, 'invalid'));

        $this->assertSame($ignoreProperties, $settings->getIgnoreProperties(Src::class, Dest::class));
        $this->assertSame($ignoreProperties, $settings->getIgnoreProperties(new Src(), new Dest()));
        $this->assertSame([], $settings->getIgnoreProperties(Src::class, 'invalid'));

        $this->assertSame($mapping, $settings->getMapping(Src::class, Dest::class));
        $this->assertSame($mapping, $settings->getMapping(new Src(), new Dest()));
        $this->assertSame([], $settings->getMapping(Src::class, 'invalid'));
    }

    public function testEmpty()
    {
        $settings = new Settings([
            [
                'src' => Src::class,
            ]
        ]);

        $this->assertSame([], $settings->getProperties(Src::class, Dest::class));
        $this->assertSame([], $settings->getIgnoreProperties(Src::class, Dest::class));
        $this->assertSame([], $settings->getMapping(Src::class, Dest::class));
    }
}
