<?php

declare(strict_types=1);

namespace Kenny1911\Populate;

use Kenny1911\Populate\Settings\SettingsInterface;

class AdvancedPopulate implements PopulateInterface
{
    /** @var PopulateInterface */
    protected $populate;

    /** @var SettingsInterface */
    protected $settings;

    public function __construct(PopulateInterface $populate, SettingsInterface $settings)
    {
        $this->populate = $populate;
        $this->settings = $settings;
    }

    public function populate(
        $src,
        $dest,
        ?array $properties = null,
        ?array $ignoreProperties = null,
        ?array $mapping = null
    ): void
    {
        if (is_object($src)) {
            $properties = $properties ?? [];
            $ignoreProperties = $ignoreProperties ?? [];
            $mapping = $mapping ?? [];

            $properties = $properties ?: $this->settings->getProperties($src, $dest);
            $ignoreProperties = $ignoreProperties ?: $this->settings->getIgnoreProperties($src, $dest);
            $mapping = array_merge($this->settings->getMapping($src, $dest), $mapping);
        }

        $this->populate->populate($src, $dest, $properties, $ignoreProperties, $mapping);
    }
}