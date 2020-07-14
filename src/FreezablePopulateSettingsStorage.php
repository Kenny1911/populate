<?php

declare(strict_types=1);

namespace Kenny1911\Populate;

class FreezablePopulateSettingsStorage implements PopulateSettingsStorageInterface, FreezableInterface
{
    use FreezableTrait;

    const FROZEN_STORAGE_MESSAGE = 'Storage cannot be changed after frozen.';

    /** @var PopulateSettingsStorageInterface */
    protected $settings;

    public function __construct(PopulateSettingsStorageInterface $storage)
    {
        $this->settings = $storage;
    }

    /**
     * @inheritDoc
     */
    public function getProperties($src, $dest): ?array
    {
        return $this->settings->getProperties($src, $dest);
    }

    /**
     * @inheritDoc
     */
    public function setProperties($src, $dest, ?array $properties): void
    {
        $this->throwFrozenException(static::FROZEN_STORAGE_MESSAGE);

        $this->settings->setProperties($src, $dest, $properties);
    }

    /**
     * @inheritDoc
     */
    public function getMapping($src, $dest): array
    {
        return $this->settings->getMapping($src, $dest);
    }

    /**
     * @inheritDoc
     */
    public function setMapping($src, $dest, array $mapping): void
    {
        $this->throwFrozenException(static::FROZEN_STORAGE_MESSAGE);

        $this->settings->setMapping($src, $dest, $mapping);
    }
}