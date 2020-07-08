<?php

declare(strict_types=1);

namespace Kenny1911\Populate;

class AdvancedPopulate implements PopulateInterface
{
    /** @var PopulateInterface */
    protected $populate;

    /** @var PopulateSettingsStorageInterface */
    protected $settings;

    public function __construct(PopulateInterface $populate, PopulateSettingsStorageInterface $settings)
    {
        $this->populate = $populate;
        $this->settings = $settings;
    }

    public function populate($src, $dest, ?array $properties = null, array $mapping = []): void
    {
        $properties = $properties ?? $this->settings->getProperties($src, $dest);
        $mapping = array_merge($this->settings->getMapping($src, $dest), $mapping);

        $this->populate->populate($src, $dest, $properties, $mapping);
    }
}