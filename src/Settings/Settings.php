<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Settings;

class Settings implements SettingsInterface
{
    const KEY_SRC = 'src';
    const KEY_DEST = 'dest';
    const KEY_PROPERTIES = 'properties';
    const KEY_IGNORE_PROPERTIES = 'ignore_properties';
    const KEY_MAPPING = 'mapping';

    /** @var array<string> */
    private $properties = [];

    /** @var array<string> */
    private $ignoreProperties = [];

    /** @var array<string> */
    private $mappings = [];

    /**
     * Options:
     *
     * [
     *      [
     *          'src' => Namespace\Src
     *          'dest' => Namespace\Dest,
     *          'properties' => ['prop1', 'prop2'] | null
     *          'ignore_properties' => ['prop3'] | null
     *          'mapping' => ['prop1' => 'newProp1'] | null
     *      ]
     * ]
     *
     * @param array $settings
     */
    public function __construct(array $settings = [])
    {
        foreach ($settings as $setting) {
            $src = $setting[static::KEY_SRC] ?? null;
            $dest = $setting[static::KEY_DEST] ?? null;

            if (!$src || !$dest) {
                continue;
            }

            if (isset($setting[static::KEY_PROPERTIES])) {
                $this->setProperties($src, $dest, (array)$setting[static::KEY_PROPERTIES]);
            }

            if (isset($setting[static::KEY_IGNORE_PROPERTIES])) {
                $this->setIgnoreProperties($src, $dest, (array)$setting[static::KEY_IGNORE_PROPERTIES]);
            }

            if (isset($setting[static::KEY_MAPPING])) {
                $this->setMapping($src, $dest, (array)$setting[static::KEY_MAPPING]);
            }
        }
    }

    public function getProperties($src, $dest): array
    {
        return $this->properties[static::key($src, $dest)] ?? [];
    }

    public function getIgnoreProperties($src, $dest): array
    {
        return $this->ignoreProperties[static::key($src, $dest)] ?? [];
    }

    public function getMapping($src, $dest): array
    {
        return $this->mappings[static::key($src, $dest)] ?? [];
    }

    private function setProperties($src, $dest, array $properties): void
    {
        $this->properties[static::key($src, $dest)] = $properties;
    }

    private function setIgnoreProperties($src, $dest, array $properties): void
    {
        $this->ignoreProperties[static::key($src, $dest)] = $properties;
    }

    private function setMapping($src, $dest, array $mapping): void
    {
        $this->mappings[static::key($src, $dest)] = $mapping;
    }

    /**
     * @param object|string $src
     * @param object|string $dest
     *
     * @return string
     */
    protected static function key($src, $dest): string
    {
        $src = is_object($src) ? get_class($src) : $src;
        $dest = is_object($dest) ? get_class($dest) : $dest;

        return $src.'::'.$dest;
    }
}