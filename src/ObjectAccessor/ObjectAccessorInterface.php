<?php

declare(strict_types=1);

namespace Kenny1911\Populate\ObjectAccessor;

interface ObjectAccessorInterface
{
    /**
     * Get associative array with properties of object.
     *
     * @param object $src
     * @param string[]|null $properties
     * @param array $mapping
     *
     * @return array
     */
    public function getData($src, ?array $properties = null, array $mapping = []): array;

    /**
     * Set object properties value from associative array data.
     *
     * @param object $dest
     * @param array  $data
     */
    public function setData($dest, array $data): void;
}