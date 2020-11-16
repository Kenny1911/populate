<?php

declare(strict_types=1);

namespace Kenny1911\Populate;

use Kenny1911\Populate\SettingsStorage\SettingsStorageInterface;

class AdvancedPopulate implements PopulateInterface
{
    /** @var PopulateInterface */
    protected $populate;

    /** @var SettingsStorageInterface */
    protected $settings;

    public function __construct(PopulateInterface $populate, SettingsStorageInterface $settings)
    {
        $this->populate = $populate;
        $this->settings = $settings;
    }

    public function populate(
        $src,
        $dest,
        ?array $properties = null,
        array $ignoreProperties = [],
        array $mapping = []
    ): void
    {
        $properties = $properties ?? $this->settings->getProperties($src, $dest);
        // TODO get $ignoreProperties from settings
        $mapping = array_merge($this->settings->getMapping($src, $dest), $mapping);

        $this->populate->populate($src, $dest, $properties, $ignoreProperties, $mapping);
    }
}