<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Tests\SettingsStorage;

use Kenny1911\Populate\Exception\FrozenException;
use Kenny1911\Populate\SettingsStorage\FreezableSettingsStorage;
use Kenny1911\Populate\SettingsStorage\SettingsStorage;
use PHPUnit\Framework\TestCase;

class FreezableSettingsStorageTest extends TestCase
{
    /** @var object */
    private $src;

    /** @var object */
    private $dest;

    /** @var FreezableSettingsStorage */
    private $settings;

    public function testSetProperties()
    {
        $properties = ['foo', 'bar', 'baz'];
        $this->settings->setProperties($this->src, $this->dest, $properties);

        $this->assertSame($properties, $this->settings->getProperties($this->src, $this->dest));
    }

    public function testSetPropertiesFrozenStorage()
    {
        $this->expectException(FrozenException::class);
        $this->expectExceptionMessage(FreezableSettingsStorage::FROZEN_STORAGE_MESSAGE);
        $this->expectExceptionCode(0);

        $this->settings->freeze();

        $this->settings->setProperties($this->src, $this->dest, ['foo', 'bar', 'baz']);
    }

    public function testSetMapping()
    {
        $mapping = ['foo' => 'bar', 'bar' => 'baz'];
        $this->settings->setMapping($this->src, $this->dest, $mapping);

        $this->assertSame($mapping, $this->settings->getMapping($this->src, $this->dest));
    }

    public function testSetMappingFrozenStorage()
    {
        $this->expectException(FrozenException::class);
        $this->expectExceptionMessage(FreezableSettingsStorage::FROZEN_STORAGE_MESSAGE);
        $this->expectExceptionCode(0);

        $this->settings->freeze();

        $this->settings->setMapping($this->src, $this->dest, ['foo' => 'bar', 'bar' => 'baz']);
    }

    protected function setUp(): void
    {
        $this->src = new class {};
        $this->dest = new class {};

        $this->settings = new FreezableSettingsStorage(new SettingsStorage());
    }
}
