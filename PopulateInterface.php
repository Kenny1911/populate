<?php

declare(strict_types=1);

namespace Kenny1911\Populate;

interface PopulateInterface
{
    /**
     * @param object     $src
     * @param object     $dest
     * @param array|null $properties
     * @param array      $mapping
     */
    public function populate($src, $dest, ?array $properties = null, array $mapping = []): void;
}