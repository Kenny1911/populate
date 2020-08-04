<?php

declare(strict_types=1);

namespace Kenny1911\Populate;

interface PopulateSettingsStorageInterface
{
    /**
     * @param object|string $src
     * @param object|string $dest
     *
     * @return string[]
     */
    public function getProperties($src, $dest): ?array;

    /**
     * @param object|string $src
     * @param object|string $dest
     * @param array|null    $properties
     */
    public function setProperties($src, $dest, ?array $properties): void;

    /**
     * @param object|string $src
     * @param object|string $dest
     *
     * @return string[]
     */
    public function getMapping($src, $dest): array;

    /**
     * @param object|string $src
     * @param object|string $dest
     * @param string[]      $mapping
     */
    public function setMapping($src, $dest, array $mapping): void;
}