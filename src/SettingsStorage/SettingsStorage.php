<?php

declare(strict_types=1);

namespace Kenny1911\Populate\SettingsStorage;

class SettingsStorage implements SettingsStorageInterface
{
    /** @var array<string> */
    protected $properties = [];

    /** @var array<string> */
    protected $mappings = [];

    public function getProperties($src, $dest): ?array
    {
        return $this->properties[static::key($src, $dest)] ?? null;
    }

    public function setProperties($src, $dest, ?array $properties): void
    {
        if (is_null($properties)) {
            unset($this->properties[static::key($src, $dest)]);
        } else {
            $this->properties[static::key($src, $dest)] = $properties;
        }
    }

    public function getMapping($src, $dest): array
    {
        return $this->mappings[static::key($src, $dest)] ?? [];
    }

    public function setMapping($src, $dest, array $mapping): void
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