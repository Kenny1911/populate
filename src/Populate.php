<?php

declare(strict_types=1);

namespace Kenny1911\Populate;

use Kenny1911\Populate\ObjectAccessor\ObjectAccessorInterface;

class Populate implements PopulateInterface
{
    /** @var ObjectAccessorInterface */
    protected $accessor;

    public function __construct(ObjectAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @inheritDoc
     */
    public function populate(
        $src,
        $dest,
        ?array $properties = null,
        ?array $ignoreProperties = null,
        ?array $mapping = null
    ): void
    {
        $properties = $properties ?? [];
        $ignoreProperties = $ignoreProperties ?? [];
        $mapping = $mapping ?? [];

        $data = is_object($src) ? $this->accessor->getData($src, $properties, $ignoreProperties, $mapping) : $src;

        $this->accessor->setData($dest, $data);
    }
}