<?php

declare(strict_types=1);

use Kenny1911\Populate\PopulateSettingsStorage;
use PHPUnit\Framework\TestCase;

class PopulateSettingsStorageTest extends TestCase
{
    /** @var object */
    private $src;

    /** @var object */
    private $dest;

    /** @var PopulateSettingsStorage */
    private $settings;

    public function testSetProperties()
    {
        $this->assertNull($this->settings->getProperties($this->src, $this->dest));
        $this->assertNull($this->settings->getProperties(get_class($this->src), get_class($this->dest)));

        $properties = ['foo', 'bar', 'baz'];
        $this->settings->setProperties($this->src, $this->dest, $properties);

        $this->assertSame($properties, $this->settings->getProperties($this->src, $this->dest));
        $this->assertSame($properties, $this->settings->getProperties(get_class($this->src), get_class($this->dest)));

        $properties = ['foo'];
        $this->settings->setProperties(get_class($this->src), get_class($this->dest), $properties);

        $this->assertSame($properties, $this->settings->getProperties($this->src, $this->dest));
        $this->assertSame($properties, $this->settings->getProperties(get_class($this->src), get_class($this->dest)));

        $this->settings->setProperties($this->src, $this->dest, null);

        $this->assertNull($this->settings->getProperties($this->src, $this->dest));
        $this->assertNull($this->settings->getProperties(get_class($this->src), get_class($this->dest)));
    }

    public function testSetMapping()
    {
        $this->assertSame([], $this->settings->getMapping($this->src, $this->dest));

        $mapping = ['foo' => 'bar', 'bar' => 'baz'];
        $this->settings->setMapping($this->src, $this->dest, $mapping);

        $this->assertSame($mapping, $this->settings->getMapping($this->src, $this->dest));
        $this->assertSame($mapping, $this->settings->getMapping(get_class($this->src), get_class($this->dest)));

        $mapping = ['foo' => 'baz'];
        $this->settings->setMapping(get_class($this->src), get_class($this->dest), $mapping);

        $this->assertSame($mapping, $this->settings->getMapping($this->src, $this->dest));
        $this->assertSame($mapping, $this->settings->getMapping(get_class($this->src), get_class($this->dest)));
    }

    protected function setUp(): void
    {
        $this->src = new class {};
        $this->dest = new class {};
        $this->settings = new PopulateSettingsStorage();
    }
}
