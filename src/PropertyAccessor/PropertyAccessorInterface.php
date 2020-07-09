<?php

declare(strict_types=1);

namespace Kenny1911\Populate\PropertyAccessor;

interface PropertyAccessorInterface
{
    /**
     * Get value of object property.
     *
     * @param object $src
     * @param string $name
     *
     * @return mixed
     */
    public function getValue($src, string $name);

    /**
     * Check, that object has property can be get.
     *
     * @param object $src
     * @param string $name
     *
     * @return bool
     */
    public function isReadable($src, string $name): bool;

    /**
     * Set value of object property.
     *
     * @param object $src
     * @param string $name
     * @param        $value
     */
    public function setValue($src, string $name, $value): void;

    /**
     * Check, that object has property can be set.
     *
     * @param object $src
     * @param string $name
     *
     * @return bool
     */
    public function isWritable($src, string $name): bool;
}