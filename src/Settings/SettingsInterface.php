<?php

declare(strict_types=1);

namespace Kenny1911\Populate\Settings;

interface SettingsInterface
{
    /**
     * @param object|string $src
     * @param object|string $dest
     *
     * @return string[]|null
     */
    public function getProperties($src, $dest): array;

    /**
     * @param object|string $src
     * @param object|string $dest
     *
     * @return string[]
     */
    public function getIgnoreProperties($src, $dest): array;

    /**
     * @param object|string $src
     * @param object|string $dest
     *
     * @return string[]
     */
    public function getMapping($src, $dest): array;
}