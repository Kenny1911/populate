<?php

declare(strict_types=1);


use Kenny1911\Populate\Exception\FrozenException;
use Kenny1911\Populate\FreezablePopulateSettingsStorage;
use Kenny1911\Populate\PopulateSettingsStorage;
use PHPUnit\Framework\TestCase;

class FreezablePopulateSettingsStorageTest extends TestCase
{
    /** @var object */
    private $src;

    /** @var object */
    private $dest;

    /** @var FreezablePopulateSettingsStorage */
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
        $this->expectExceptionMessage(FreezablePopulateSettingsStorage::FROZEN_STORAGE_MESSAGE);
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
        $this->expectExceptionMessage(FreezablePopulateSettingsStorage::FROZEN_STORAGE_MESSAGE);
        $this->expectExceptionCode(0);

        $this->settings->freeze();

        $this->settings->setMapping($this->src, $this->dest, ['foo' => 'bar', 'bar' => 'baz']);
    }

    protected function setUp(): void
    {
        $this->src = new class {};
        $this->dest = new class {};

        $this->settings = new FreezablePopulateSettingsStorage(new PopulateSettingsStorage());
    }
}
